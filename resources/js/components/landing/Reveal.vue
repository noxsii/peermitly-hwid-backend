<script setup lang="ts">
import { useScrollReveal } from "@/composables/useScrollReveal";

withDefaults(
    defineProps<{
        delay?: number;
    }>(),
    {
        delay: 0,
    },
);

const { target, isVisible } = useScrollReveal();
</script>

<template>
    <div
        ref="target"
        class="transition-all duration-700 ease-out motion-reduce:transition-none"
        :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'"
        :style="{ transitionDelay: `${delay}ms` }"
    >
        <slot />
    </div>
</template>
