import { usePage } from "@inertiajs/vue3";
import { computed, type ComputedRef } from "vue";
import type { PageProps, UserRole } from "@/types";

/**
 * Composable for role checks based on the Inertia-shared auth user.
 *
 * Usage:
 *   const { role, isAdmin, isSuperAdmin, isUser, hasRole, isRole } = useRole();
 *   const isStaff = hasRole(["admin", "super_admin"]); // ComputedRef<boolean>
 */
export function useRole() {
    const page = usePage<PageProps>();

    const role = computed<UserRole | null>(
        () => page.props.auth?.user?.role ?? null,
    );

    const isUser = computed(() => role.value === "user");
    const isAdmin = computed(() => role.value === "admin");
    const isSuperAdmin = computed(() => role.value === "super_admin");

    const isRole = (expected: UserRole): ComputedRef<boolean> =>
        computed(() => role.value === expected);

    const hasRole = (expected: UserRole | UserRole[]): ComputedRef<boolean> => {
        const list = Array.isArray(expected) ? expected : [expected];
        return computed(() =>
            role.value !== null ? list.includes(role.value) : false,
        );
    };

    return {
        role,
        isUser,
        isAdmin,
        isSuperAdmin,
        isRole,
        hasRole,
    };
}
