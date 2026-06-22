import { startAuthentication } from "@simplewebauthn/browser";
import { onMounted, ref } from "vue";
import { postJson } from "@/lib/passkeyHttp";

export function usePasskeyAuth() {
    const passkeysSupported = ref(false);
    const processing = ref(false);
    const error = ref<string | null>(null);

    onMounted(() => {
        passkeysSupported.value =
            typeof window !== "undefined" &&
            typeof window.PublicKeyCredential !== "undefined";
    });

    async function loginWithPasskey(): Promise<void> {
        if (!passkeysSupported.value || processing.value) {
            return;
        }

        processing.value = true;
        error.value = null;

        try {
            const opts = await postJson<Record<string, unknown>>(
                "/auth/passkey/options",
            );
            const assertion = await startAuthentication({ optionsJSON: opts });
            const result = await postJson<{ redirect: string }>(
                "/auth/passkey/verify",
                { assertion },
            );
            window.location.href = result.redirect;
        } catch (e) {
            const err = e as { name?: string };
            if (err?.name === "NotAllowedError") {
                return;
            }
            error.value =
                "Passkey sign-in failed. Try again or use email + password.";
        } finally {
            processing.value = false;
        }
    }

    return { passkeysSupported, processing, error, loginWithPasskey };
}
