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
            class="text-muted-foreground text-xs font-semibold tracking-[0.14em] uppercase"
        >
            On this page
        </p>
        <ul class="space-y-1.5 text-sm">
            <li
                v-for="heading in items"
                :key="heading.id"
                :style="{ paddingLeft: heading.depth === 3 ? '0.75rem' : '0' }"
            >
                <a
                    :href="`#${heading.id}`"
                    class="block transition-colors"
                    :class="
                        activeId === heading.id
                            ? 'text-primary font-medium'
                            : 'text-muted-foreground hover:text-foreground'
                    "
                >
                    {{ heading.text }}
                </a>
            </li>
        </ul>
    </div>
</template>
