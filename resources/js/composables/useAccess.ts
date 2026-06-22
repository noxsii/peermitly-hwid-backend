import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import type { DashboardSubscription, PageProps } from "@/types";

/**
 * Composable for access checks based on the Inertia-shared auth payload.
 * A user "has access" when their account is active AND they hold an active,
 * non-expired subscription (both shared on every request).
 *
 * Usage:
 *   const { isActive, hasSubscription, hasAccess, subscription } = useAccess();
 */
export function useAccess() {
    const page = usePage<PageProps>();

    const isActive = computed(() => page.props.auth?.user?.is_active === true);

    const subscription = computed<DashboardSubscription | null>(
        () => page.props.auth?.subscription ?? null,
    );

    const hasSubscription = computed(() => subscription.value != null);

    const hasAccess = computed(() => isActive.value && hasSubscription.value);

    return {
        isActive,
        subscription,
        hasSubscription,
        hasAccess,
    };
}
