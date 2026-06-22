import { useClipboard } from "@vueuse/core";
import { ref, type Ref } from "vue";

/**
 * Copies text to the clipboard and exposes a transient `copied` flag
 * that resets after the given timeout (default 2000ms).
 */
export function useCopyToClipboard(timeout = 2000): {
    copied: Ref<boolean>;
    copy: (text: string) => Promise<void>;
} {
    const { copy: write } = useClipboard();
    const copied = ref(false);
    let timer: ReturnType<typeof setTimeout> | null = null;

    async function copy(text: string): Promise<void> {
        try {
            await write(text);
        } catch {
            // Clipboard can reject in insecure contexts or when permission is
            // denied — fail silently rather than surfacing an unhandled rejection.
            return;
        }
        copied.value = true;
        if (timer) {
            clearTimeout(timer);
        }
        timer = setTimeout(() => {
            copied.value = false;
        }, timeout);
    }

    return { copied, copy };
}
