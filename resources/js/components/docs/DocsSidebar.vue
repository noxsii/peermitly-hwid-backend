<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import type { DocSection } from "@/types";

defineProps<{
    sections: DocSection[];
    currentSlug: string;
}>();

defineEmits<{
    navigate: [];
}>();
</script>

<template>
    <nav class="space-y-8">
        <div v-for="section in sections" :key="section.title">
            <p
                class="text-muted-foreground mb-3 flex items-center gap-2 text-[11px] font-semibold tracking-[0.16em] uppercase"
            >
                {{ section.title }}
            </p>
            <ul class="space-y-0.5">
                <li v-for="item in section.items" :key="item.slug">
                    <Link
                        :href="`/guide/${item.slug}`"
                        preserve-state
                        class="group relative block border-l px-3 py-2 text-sm transition-colors"
                        :class="
                            item.slug === currentSlug
                                ? 'border-primary text-foreground font-semibold'
                                : 'border-transparent text-muted-foreground hover:text-foreground hover:border-border'
                        "
                        @click="$emit('navigate')"
                    >
                        <span
                            v-if="item.slug === currentSlug"
                            class="bg-primary absolute inset-y-0 -left-px w-px"
                        />
                        <span class="inline-flex items-center gap-1.5">
                            {{ item.title }}
                            <span
                                v-if="item.pro"
                                class="bg-primary text-primary-foreground rounded-full px-1.5 py-0.5 text-[9px] font-bold tracking-wide uppercase"
                            >
                                Pro
                            </span>
                        </span>
                    </Link>
                </li>
            </ul>
        </div>
    </nav>
</template>
