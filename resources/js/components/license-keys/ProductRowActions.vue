<script setup lang="ts">
import { Pencil, Trash2 } from "@lucide/vue";
import { ref } from "vue";
import ConfirmDialog from "@/components/dialogs/ConfirmDialog.vue";
import EditProductDialog from "@/components/dialogs/EditProductDialog.vue";
import { Button } from "@/components/ui/button";
import type { ProductOption } from "@/types";

const props = defineProps<{
    product: ProductOption;
}>();

const emit = defineEmits<{
    confirmDelete: [uuid: string];
}>();

const editOpen = ref(false);
const deleteOpen = ref(false);

const onConfirm = () => {
    emit("confirmDelete", props.product.uuid);
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

        <EditProductDialog v-model:open="editOpen" :product="product" />

        <ConfirmDialog
            v-model:open="deleteOpen"
            title="Delete product?"
            :description="`This will permanently remove '${product.name}'. License keys for this product will keep their reference but become orphaned.`"
            confirm-label="Delete"
            destructive
            @confirm="onConfirm"
        />
    </div>
</template>
