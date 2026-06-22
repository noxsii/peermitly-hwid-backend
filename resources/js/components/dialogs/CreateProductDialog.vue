<script setup lang="ts">
import ProductForm from "@/components/license-keys/ProductForm.vue";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";

const open = defineModel<boolean>("open", { required: true });

const onSuccess = () => {
    open.value = false;
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle>New Product</DialogTitle>
                <DialogDescription>
                    Products group license keys. The slug is used by external
                    software to validate that a key belongs to the right
                    product.
                </DialogDescription>
            </DialogHeader>

            <ProductForm
                action="/license-keys/products"
                submit-label="Create product"
                @success="onSuccess"
            />
        </DialogContent>
    </Dialog>
</template>
