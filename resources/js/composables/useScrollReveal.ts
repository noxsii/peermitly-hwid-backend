import {
    useIntersectionObserver,
    usePreferredReducedMotion,
} from "@vueuse/core";
import { ref, type Ref } from "vue";

/**
 * Reveals an element when it scrolls into view. Respects prefers-reduced-motion:
 * when the user prefers reduced motion, the element is visible immediately.
 *
 * Attach the returned `target` ref to a section root and bind `isVisible`
 * to transition utilities, e.g. :class="isVisible ? 'opacity-100' : 'opacity-0'".
 */
export function useScrollReveal(): {
    target: Ref<HTMLElement | null>;
    isVisible: Ref<boolean>;
} {
    const reducedMotion = usePreferredReducedMotion();
    const target = ref<HTMLElement | null>(null);
    const isVisible = ref(reducedMotion.value === "reduce");

    const { stop } = useIntersectionObserver(
        target,
        ([entry]) => {
            if (entry?.isIntersecting) {
                isVisible.value = true;
                stop();
            }
        },
        { threshold: 0.15 },
    );

    return { target, isVisible };
}
