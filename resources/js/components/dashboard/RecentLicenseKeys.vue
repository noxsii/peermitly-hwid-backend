<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { ChevronRight, KeyRound } from "@lucide/vue";
import LicenseKeyStatusBadge from "@/components/license-keys/LicenseKeyStatusBadge.vue";
import type { LicenseKey } from "@/types";

defineProps<{
    rows: LicenseKey[];
}>();

const formatDate = (iso: string | null): string => {
    if (!iso) return "—";
    try {
        return new Date(iso).toLocaleDateString(undefined, {
            month: "short",
            day: "numeric",
        });
    } catch {
        return "—";
    }
};
</script>

<template>
    <ul v-if="rows.length" class="divide-border divide-y">
        <li
            v-for="row in rows"
            :key="row.uuid"
            class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0"
        >
            <Link
                :href="`/license-keys/${row.uuid}`"
                class="hover:text-foreground flex min-w-0 flex-1 items-center gap-3 transition-colors"
            >
                <span
                    class="bg-primary/10 text-primary flex size-9 shrink-0 items-center justify-center rounded-lg"
                    aria-hidden="true"
                >
                    <KeyRound class="size-4" />
                </span>
                <div class="min-w-0 flex-1">
                    <p
                        class="text-foreground truncate font-mono text-sm font-medium"
                    >
                        {{ row.key }}
                    </p>
                    <p class="text-muted-foreground truncate text-xs">
                        {{ row.product?.name ?? "—" }}
                        <template v-if="row.customer">
                            · {{ row.customer.email }}
                        </template>
                        <template v-if="row.expires_at">
                            · expires {{ formatDate(row.expires_at) }}
                        </template>
                    </p>
                </div>
            </Link>
            <div class="flex items-center gap-2 shrink-0">
                <LicenseKeyStatusBadge :status="row.status" />
                <ChevronRight class="text-muted-foreground size-4" />
            </div>
        </li>
    </ul>

    <div
        v-else
        class="text-muted-foreground flex flex-col items-center gap-2 py-10 text-center"
    >
        <KeyRound class="size-6" aria-hidden="true" />
        <p class="text-sm">No license keys yet.</p>
        <Link
            href="/license-keys"
            class="text-primary text-xs font-medium hover:underline"
        >
            Create the first one →
        </Link>
    </div>
</template>
