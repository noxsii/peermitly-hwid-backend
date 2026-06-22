<script setup lang="ts">
import { useSlots } from "vue";

defineProps<{
    title?: string;
}>();

const slots = useSlots();
const hasHeader = (title?: string) => Boolean(title) || Boolean(slots.actions);
</script>

<template>
    <section
        class="bg-muted/60 text-foreground flex flex-col gap-3 rounded-2xl p-3"
    >
        <header
            v-if="hasHeader(title)"
            class="flex flex-wrap items-center justify-between gap-x-3 gap-y-2 px-1 pt-1"
        >
            <h2
                v-if="title"
                class="text-muted-foreground text-xs font-medium uppercase tracking-wide"
            >
                {{ title }}
            </h2>

            <div
                v-if="$slots.actions"
                class="flex flex-wrap items-center gap-1"
            >
                <slot name="actions" />
            </div>
        </header>

        <div class="bg-background text-foreground rounded-xl p-4 shadow-sm">
            <slot />
        </div>
    </section>
</template>
