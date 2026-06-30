<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref, watch } from "vue";
import type { DocHeading } from "@/types";

const props = defineProps<{
    headings?: DocHeading[];
}>();

const items = computed<DocHeading[]>(() => props.headings ?? []);

const activeId = ref<string>("");
let observer: IntersectionObserver | null = null;

function observe(): void {
    observer?.disconnect();

    if (items.value.length === 0) {
        return;
    }

    observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    activeId.value = entry.target.id;
                }
            }
        },
        { rootMargin: "0px 0px -75% 0px", threshold: 0 },
    );

    for (const heading of items.value) {
        const el = document.getElementById(heading.id);

        if (el) {
            observer.observe(el);
        }
    }
}

onMounted(observe);
watch(() => props.headings, observe);
onBeforeUnmount(() => observer?.disconnect());
</script>

<template>
    <div v-if="items.length" class="space-y-3">
        <p
            class="text-muted-foreground flex items-center gap-2 text-xs font-semibold tracking-[0.14em] uppercase"
        >
            <span class="bg-primary/60 size-1.5 rounded-full" />
            On this page
        </p>
        <ul class="border-border/50 space-y-0.5 border-l text-sm">
            <li
                v-for="heading in items"
                :key="heading.id"
                :style="{ paddingLeft: heading.depth === 3 ? '0.75rem' : '0' }"
            >
                <a
                    :href="`#${heading.id}`"
                    class="-ml-px block border-l-2 py-1 pl-3 transition-all"
                    :class="
                        activeId === heading.id
                            ? 'border-primary text-primary font-medium'
                            : 'text-muted-foreground hover:text-foreground hover:border-border border-transparent'
                    "
                >
                    {{ heading.text }}
                </a>
            </li>
        </ul>
    </div>
</template>
