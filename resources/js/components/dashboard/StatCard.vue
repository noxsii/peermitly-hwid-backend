<script setup lang="ts">
import type { Component } from "vue";

defineProps<{
    label: string;
    value: number | string;
    icon?: Component;
    hint?: string | null;
    tone?: "default" | "success" | "warning" | "danger";
}>();
</script>

<template>
    <div
        class="bg-card text-card-foreground border-border/70 flex flex-col gap-3 rounded-2xl border p-4 shadow-sm"
    >
        <div class="flex items-center justify-between gap-2">
            <p
                class="text-muted-foreground text-xs font-medium tracking-wide uppercase"
            >
                {{ label }}
            </p>
            <span
                v-if="icon"
                :class="[
                    'inline-flex size-8 items-center justify-center rounded-lg',
                    tone === 'success' && 'bg-emerald-500/10 text-emerald-600',
                    tone === 'warning' && 'bg-amber-500/10 text-amber-600',
                    tone === 'danger' && 'bg-rose-500/10 text-rose-600',
                    (!tone || tone === 'default') &&
                        'bg-primary/10 text-primary',
                ]"
                aria-hidden="true"
            >
                <component :is="icon" class="size-4" />
            </span>
        </div>
        <div class="flex items-baseline justify-between gap-2">
            <span
                class="text-foreground text-3xl font-semibold tracking-tight tabular-nums"
                >{{ value }}</span
            >
            <span v-if="hint" class="text-muted-foreground text-xs">{{
                hint
            }}</span>
        </div>
    </div>
</template>
