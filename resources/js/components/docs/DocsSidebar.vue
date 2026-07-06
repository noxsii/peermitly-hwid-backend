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
    <nav class="space-y-7">
        <div v-for="section in sections" :key="section.title">
            <p
                class="text-muted-foreground mb-2 flex items-center gap-2 px-3 text-xs font-semibold tracking-[0.14em] uppercase"
            >
                <span class="bg-primary/60 size-1.5 rounded-full" />
                {{ section.title }}
            </p>
            <ul class="space-y-0.5">
                <li v-for="item in section.items" :key="item.slug">
                    <Link
                        :href="`/guide/${item.slug}`"
                        preserve-state
                        class="group relative block rounded-lg px-3 py-1.5 text-sm transition-colors"
                        :class="
                            item.slug === currentSlug
                                ? 'from-primary/12 text-primary bg-gradient-to-r to-transparent font-medium'
                                : 'text-muted-foreground hover:text-foreground hover:bg-muted/50'
                        "
                        @click="$emit('navigate')"
                    >
                        <span
                            v-if="item.slug === currentSlug"
                            class="bg-primary absolute top-1/2 left-0 h-4 w-0.5 -translate-y-1/2 rounded-full"
                        />
                        <span class="inline-flex items-center gap-1.5">
                            {{ item.title }}
                            <span
                                v-if="item.pro"
                                class="from-primary to-orange-400 text-primary-foreground rounded-full bg-gradient-to-r px-1.5 py-0.5 text-[9px] font-bold tracking-wide uppercase"
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
