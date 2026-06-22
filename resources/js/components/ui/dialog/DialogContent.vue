<script setup lang="ts">
import type { DialogContentEmits, DialogContentProps } from "reka-ui";

import type { HTMLAttributes } from "vue";
import { reactiveOmit } from "@vueuse/core";
import { XIcon } from "lucide-vue-next";
import {
    DialogClose,
    DialogContent,
    DialogPortal,
    useForwardPropsEmits,
} from "reka-ui";
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import DialogOverlay from "./DialogOverlay.vue";

defineOptions({
    inheritAttrs: false,
});

const props = withDefaults(
    defineProps<
        DialogContentProps & {
            class?: HTMLAttributes["class"];
            showCloseButton?: boolean;
        }
    >(),
    {
        showCloseButton: true,
    },
);
const emits = defineEmits<DialogContentEmits>();

const delegatedProps = reactiveOmit(props, "class");

const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
    <DialogPortal>
        <DialogOverlay />
        <DialogContent
            data-slot="dialog-content"
            v-bind="{ ...$attrs, ...forwarded }"
            :class="
                cn(
                    'bg-popover text-popover-foreground data-open:animate-in data-closed:animate-out data-closed:fade-out-0 data-open:fade-in-0 data-closed:zoom-out-95 data-open:zoom-in-95 ring-foreground/10 flex max-w-[calc(100%-2rem)] max-h-[calc(100dvh-2rem)] flex-col overflow-hidden rounded-xl text-sm ring-1 duration-100 sm:max-w-md fixed top-1/2 left-1/2 z-50 w-full -translate-x-1/2 -translate-y-1/2 outline-none',
                    props.class,
                )
            "
        >
            <div
                class="grid min-h-0 flex-1 gap-6 overflow-y-auto p-6 [scrollbar-gutter:stable] [scrollbar-width:thin] [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-muted-foreground/30 hover:[&::-webkit-scrollbar-thumb]:bg-muted-foreground/50"
            >
                <slot />
            </div>

            <DialogClose
                v-if="showCloseButton"
                data-slot="dialog-close"
                as-child
            >
                <Button
                    variant="ghost"
                    class="absolute top-4 right-4 z-10"
                    size="icon-sm"
                >
                    <XIcon />
                    <span class="sr-only">Close</span>
                </Button>
            </DialogClose>
        </DialogContent>
    </DialogPortal>
</template>
