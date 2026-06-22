<script setup lang="ts">
import { Loader2, Pencil, RotateCcw, Trash2 } from "@lucide/vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import Card from "@/components/Card.vue";
import { Button } from "@/components/ui/button";
import type { LicenseKey } from "@/types";

const props = defineProps<{
    licenseKey: LicenseKey;
}>();

defineEmits<{
    edit: [];
    delete: [];
}>();

const canRestore = props.licenseKey.status === "revoked";
const restoring = ref(false);

function restore(): void {
    router.post(
        `/license-keys/${props.licenseKey.uuid}/restore`,
        {},
        {
            preserveScroll: true,
            onStart: () => (restoring.value = true),
            onFinish: () => (restoring.value = false),
        },
    );
}
</script>

<template>
    <Card title="Actions">
        <div class="flex flex-col gap-2">
            <Button
                variant="ghost"
                class="w-full justify-start"
                type="button"
                @click="$emit('edit')"
            >
                <Pencil class="size-4" />
                Edit settings
            </Button>
            <Button
                v-if="canRestore"
                variant="ghost"
                class="w-full justify-start"
                type="button"
                :disabled="restoring"
                @click="restore"
            >
                <Loader2 v-if="restoring" class="size-4 animate-spin" />
                <RotateCcw v-else class="size-4" />
                Restore
            </Button>
            <Button
                variant="ghost"
                class="text-destructive hover:text-destructive w-full justify-start"
                type="button"
                @click="$emit('delete')"
            >
                <Trash2 class="size-4" />
                Delete key
            </Button>
        </div>
    </Card>
</template>
