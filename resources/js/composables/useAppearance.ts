import { useColorMode } from "@vueuse/core";
import type { Appearance } from "@/types";

const STORAGE_KEY = "appearance";

export function useAppearance() {
    const mode = useColorMode<Appearance>({
        attribute: "class",
        selector: "html",
        storageKey: STORAGE_KEY,
        initialValue: "auto",
        modes: {
            light: "",
            dark: "dark",
            auto: "",
        },
    });

    function set(value: Appearance): void {
        mode.value = value;
    }

    function toggle(): void {
        mode.value = mode.value === "dark" ? "light" : "dark";
    }

    return { mode, set, toggle };
}
