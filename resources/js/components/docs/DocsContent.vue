<script setup lang="ts">
import { Check, Copy } from "@lucide/vue";
import { createApp, h, nextTick, onMounted, ref, watch } from "vue";

const props = defineProps<{
    html: string;
}>();

const container = ref<HTMLElement | null>(null);

function mountCopyButton(pre: HTMLElement): void {
    const holder = document.createElement("div");
    holder.className = "docs-copy";
    pre.appendChild(holder);

    const copied = ref(false);

    const app = createApp({
        setup() {
            const copy = async (): Promise<void> => {
                const code = pre.querySelector("code");
                await navigator.clipboard.writeText(
                    (code?.textContent ?? pre.textContent ?? "").replace(
                        /\n$/,
                        "",
                    ),
                );
                copied.value = true;
                window.setTimeout(() => {
                    copied.value = false;
                }, 1500);
            };

            return () =>
                h(
                    "button",
                    {
                        type: "button",
                        "aria-label": "Copy code",
                        class: "docs-copy-btn",
                        onClick: copy,
                    },
                    [h(copied.value ? Check : Copy, { class: "size-3.5" })],
                );
        },
    });

    app.mount(holder);
}

function enhance(): void {
    const root = container.value;

    if (!root) {
        return;
    }

    root.querySelectorAll<HTMLElement>("pre.shiki").forEach((pre) => {
        if (pre.dataset.enhanced === "true") {
            return;
        }

        pre.dataset.enhanced = "true";
        mountCopyButton(pre);
    });
}

onMounted(enhance);
watch(
    () => props.html,
    () => nextTick(enhance),
);
</script>

<template>
    <article ref="container" class="docs-prose" v-html="html" />
</template>
