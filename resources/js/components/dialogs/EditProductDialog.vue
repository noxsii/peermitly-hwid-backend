<script setup lang="ts">
import ProductForm from "@/components/license-keys/ProductForm.vue";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import type { ProductOption } from "@/types";

const props = defineProps<{
    product: ProductOption;
}>();

const open = defineModel<boolean>("open", { required: true });

const onSuccess = () => {
    open.value = false;
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle>Edit Product</DialogTitle>
                <DialogDescription>
                    Changing the slug breaks any external software that uses the
                    old slug. Update both ends in sync.
                </DialogDescription>
            </DialogHeader>

            <ProductForm
                :action="`/license-keys/products/${product.uuid}`"
                method="patch"
                submit-label="Save changes"
                :initial="product"
                @success="onSuccess"
            />
        </DialogContent>
    </Dialog>
</template>
