<script setup lang="ts">
import CustomerForm from "@/components/license-keys/CustomerForm.vue";
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
                <DialogTitle>New Customer</DialogTitle>
                <DialogDescription>
                    Customers receive license keys. Email is required and must
                    be unique within your team.
                </DialogDescription>
            </DialogHeader>

            <CustomerForm
                action="/license-keys/customers"
                submit-label="Create customer"
                @success="onSuccess"
            />
        </DialogContent>
    </Dialog>
</template>
