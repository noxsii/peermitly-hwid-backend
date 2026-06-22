<script setup lang="ts">
import { Pencil, Trash2 } from "@lucide/vue";
import { ref } from "vue";
import ConfirmDialog from "@/components/dialogs/ConfirmDialog.vue";
import EditLicenseKeyTypeDialog from "@/components/dialogs/EditLicenseKeyTypeDialog.vue";
import { Button } from "@/components/ui/button";
import type { LicenseKeyType } from "@/types";

const props = defineProps<{
    type: LicenseKeyType;
}>();

const emit = defineEmits<{
    confirmDelete: [uuid: string];
}>();

const editOpen = ref(false);
const deleteOpen = ref(false);

const onConfirm = () => {
    emit("confirmDelete", props.type.uuid);
};
</script>

<template>
    <div class="flex justify-end gap-1">
        <Button
            variant="ghost"
            size="icon-sm"
            type="button"
            @click="editOpen = true"
        >
            <Pencil class="size-4" />
        </Button>
        <Button
            variant="ghost"
            size="icon-sm"
            type="button"
            @click="deleteOpen = true"
        >
            <Trash2 class="text-destructive size-4" />
        </Button>

        <EditLicenseKeyTypeDialog v-model:open="editOpen" :type="type" />

        <ConfirmDialog
            v-model:open="deleteOpen"
            title="Delete license key type?"
            :description="`This will permanently remove '${type.name}'. License keys created from this type are not affected.`"
            confirm-label="Delete"
            destructive
            @confirm="onConfirm"
        />
    </div>
</template>
