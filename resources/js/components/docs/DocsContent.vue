<script setup lang="ts">
import { Check, Copy, X } from "@lucide/vue";
import {
    createApp,
    h,
    nextTick,
    onBeforeUnmount,
    onMounted,
    ref,
    watch,
} from "vue";

const props = defineProps<{
    html: string;
}>();

const container = ref<HTMLElement | null>(null);

const zoomedSrc = ref<string | null>(null);
const zoomedAlt = ref("");

function onContentClick(event: MouseEvent): void {
    const image = (event.target as HTMLElement).closest("img");

    if (!image || !container.value?.contains(image)) {
        return;
    }

    zoomedSrc.value = image.getAttribute("src");
    zoomedAlt.value = image.getAttribute("alt") ?? "";
}

function closeZoom(): void {
    zoomedSrc.value = null;
}

function onKeydown(event: KeyboardEvent): void {
    if (event.key === "Escape") {
        closeZoom();
    }
}

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

onMounted(() => {
    enhance();
    window.addEventListener("keydown", onKeydown);
});
onBeforeUnmount(() => {
    window.removeEventListener("keydown", onKeydown);
});
watch(
    () => props.html,
    () => {
        closeZoom();
        void nextTick(enhance);
    },
);
</script>

<template>
    <article
        ref="container"
        class="docs-prose"
        v-html="html"
        @click="onContentClick"
    />

    <Teleport to="body">
        <Transition
            enter-active-class="transition-opacity duration-200"
            enter-from-class="opacity-0"
            leave-active-class="transition-opacity duration-150"
            leave-to-class="opacity-0"
        >
            <div
                v-if="zoomedSrc"
                role="dialog"
                aria-modal="true"
                :aria-label="zoomedAlt || 'Zoomed image'"
                class="fixed inset-0 z-50 flex cursor-zoom-out items-center justify-center bg-black/80 p-4 backdrop-blur-sm sm:p-10"
                @click="closeZoom"
            >
                <button
                    type="button"
                    aria-label="Close"
                    class="absolute top-4 right-4 rounded-full bg-white/10 p-2 text-white transition-colors hover:bg-white/20"
                    @click.stop="closeZoom"
                >
                    <X class="size-5" />
                </button>
                <img
                    :src="zoomedSrc"
                    :alt="zoomedAlt"
                    class="max-h-full max-w-full rounded-lg object-contain shadow-2xl"
                />
            </div>
        </Transition>
    </Teleport>
</template>
