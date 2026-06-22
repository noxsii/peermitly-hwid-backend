import { router } from "@inertiajs/vue3";
import { startRegistration } from "@simplewebauthn/browser";
import { onMounted, ref } from "vue";
import { postJson } from "@/lib/passkeyHttp";

export function usePasskeyManagement() {
    const passkeysSupported = ref(false);
    const processing = ref(false);
    const error = ref<string | null>(null);

    onMounted(() => {
        passkeysSupported.value =
            typeof window !== "undefined" &&
            typeof window.PublicKeyCredential !== "undefined";
    });

    async function register(name: string): Promise<boolean> {
        if (!passkeysSupported.value || processing.value) {
            return false;
        }

        processing.value = true;
        error.value = null;

        try {
            const opts = await postJson<Record<string, unknown>>(
                "/settings/passkeys/options",
            );
            const credential = await startRegistration({ optionsJSON: opts });
            await postJson("/settings/passkeys", { name, credential });
            router.reload({ only: ["passkeys"] });
            return true;
        } catch (e) {
            const err = e as { name?: string };
            if (err?.name === "NotAllowedError") {
                return false;
            }
            error.value = "Could not register the passkey. Please try again.";
            return false;
        } finally {
            processing.value = false;
        }
    }

    function remove(id: number): void {
        router.delete(`/settings/passkeys/${id}`, { preserveScroll: true });
    }

    return { passkeysSupported, processing, error, register, remove };
}
