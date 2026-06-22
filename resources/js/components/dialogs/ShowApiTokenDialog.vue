<script setup lang="ts">
import { Check, Copy } from "@lucide/vue";
import { ref } from "vue";
import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";

const props = defineProps<{
    plainTextToken: string;
}>();

const open = defineModel<boolean>("open", { required: true });

const copied = ref(false);

const copy = async () => {
    await navigator.clipboard.writeText(props.plainTextToken);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>API Token Created</DialogTitle>
                <DialogDescription>
                    Copy this token now. It will not be shown again.
                </DialogDescription>
            </DialogHeader>

            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <code
                        class="bg-muted/40 flex-1 break-all rounded-md p-2 font-mono text-xs"
                    >
                        {{ plainTextToken }}
                    </code>
                    <Button
                        variant="outline"
                        size="icon-sm"
                        type="button"
                        @click="copy"
                    >
                        <Check v-if="copied" class="size-4 text-emerald-500" />
                        <Copy v-else class="size-4" />
                    </Button>
                </div>
                <p v-if="copied" class="text-xs text-emerald-500">
                    Copied to clipboard.
                </p>
            </div>

            <DialogFooter>
                <DialogClose as-child>
                    <Button type="button">Done</Button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
