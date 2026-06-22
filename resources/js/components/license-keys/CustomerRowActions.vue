<script setup lang="ts">
import { Pencil, Trash2 } from "@lucide/vue";
import { ref } from "vue";
import ConfirmDialog from "@/components/dialogs/ConfirmDialog.vue";
import EditCustomerDialog from "@/components/dialogs/EditCustomerDialog.vue";
import { Button } from "@/components/ui/button";
import type { CustomerOption } from "@/types";

const props = defineProps<{
    customer: CustomerOption;
}>();

const emit = defineEmits<{
    confirmDelete: [uuid: string];
}>();

const editOpen = ref(false);
const deleteOpen = ref(false);

const onConfirm = () => {
    emit("confirmDelete", props.customer.uuid);
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

        <EditCustomerDialog v-model:open="editOpen" :customer="customer" />

        <ConfirmDialog
            v-model:open="deleteOpen"
            title="Delete customer?"
            :description="`This will permanently remove '${customer.email}'. License keys for this customer will keep their reference but become orphaned.`"
            confirm-label="Delete"
            destructive
            @confirm="onConfirm"
        />
    </div>
</template>
