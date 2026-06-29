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
                class="text-muted-foreground mb-2 px-3 text-xs font-semibold tracking-[0.14em] uppercase"
            >
                {{ section.title }}
            </p>
            <ul class="space-y-0.5">
                <li v-for="item in section.items" :key="item.slug">
                    <Link
                        :href="`/guide/${item.slug}`"
                        class="block rounded-lg px-3 py-1.5 text-sm transition-colors"
                        :class="
                            item.slug === currentSlug
                                ? 'bg-primary/10 text-primary font-medium'
                                : 'text-muted-foreground hover:text-foreground hover:bg-muted/60'
                        "
                        @click="$emit('navigate')"
                    >
                        {{ item.title }}
                    </Link>
                </li>
            </ul>
        </div>
    </nav>
</template>
