<script setup lang="ts">
import CustomerForm from "@/components/license-keys/CustomerForm.vue";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import type { CustomerOption } from "@/types";

const props = defineProps<{
    customer: CustomerOption;
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
                <DialogTitle>Edit Customer</DialogTitle>
                <DialogDescription>
                    Update contact details or metadata. Email changes do not
                    affect existing license keys assigned to this customer.
                </DialogDescription>
            </DialogHeader>

            <CustomerForm
                :action="`/license-keys/customers/${props.customer.uuid}`"
                method="patch"
                submit-label="Save changes"
                :initial="props.customer"
                @success="onSuccess"
            />
        </DialogContent>
    </Dialog>
</template>
