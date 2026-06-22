<script setup lang="ts">
import { Signal } from "@lucide/vue";
import { Badge } from "@/components/ui/badge";
import type { DashboardApiCall } from "@/types";

defineProps<{
    rows: DashboardApiCall[];
}>();

const statusVariant = (
    status: number,
): "success" | "info" | "warning" | "destructive" | "default" => {
    if (status >= 500) return "destructive";
    if (status >= 400) return "warning";
    if (status >= 300) return "info";
    if (status >= 200) return "success";
    return "default";
};

const methodTone: Record<string, string> = {
    GET: "bg-sky-500/10 text-sky-600",
    POST: "bg-emerald-500/10 text-emerald-600",
    PATCH: "bg-amber-500/10 text-amber-600",
    PUT: "bg-amber-500/10 text-amber-600",
    DELETE: "bg-rose-500/10 text-rose-600",
};

const formatTime = (iso: string): string => {
    try {
        return new Date(iso).toLocaleTimeString(undefined, {
            hour: "2-digit",
            minute: "2-digit",
        });
    } catch {
        return "";
    }
};
</script>

<template>
    <ul v-if="rows.length" class="divide-border divide-y">
        <li
            v-for="row in rows"
            :key="row.uuid"
            class="flex items-center justify-between gap-3 py-2 first:pt-0 last:pb-0"
        >
            <div class="flex min-w-0 flex-1 items-center gap-2">
                <span
                    :class="[
                        'inline-flex h-6 w-12 shrink-0 items-center justify-center rounded font-mono text-[10px] font-semibold',
                        methodTone[row.method] ??
                            'bg-muted text-muted-foreground',
                    ]"
                >
                    {{ row.method }}
                </span>
                <p
                    class="text-foreground truncate font-mono text-xs"
                    :title="row.path"
                >
                    {{ row.path }}
                </p>
            </div>
            <div class="flex shrink-0 items-center gap-2">
                <span
                    v-if="row.duration_ms !== null"
                    class="text-muted-foreground tabular-nums text-[11px]"
                    >{{ row.duration_ms }} ms</span
                >
                <Badge :variant="statusVariant(row.status)" class="font-mono">
                    {{ row.status }}
                </Badge>
                <span
                    class="text-muted-foreground tabular-nums text-[11px] hidden sm:inline"
                    >{{ formatTime(row.created_at) }}</span
                >
            </div>
        </li>
    </ul>

    <div
        v-else
        class="text-muted-foreground flex flex-col items-center gap-2 py-8 text-center"
    >
        <Signal class="size-5" aria-hidden="true" />
        <p class="text-xs">No API calls yet for this team.</p>
    </div>
</template>
