<script setup lang="ts">
import LicenseKeyTypeForm from "@/components/license-keys/LicenseKeyTypeForm.vue";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import type { LicenseKeyType } from "@/types";

const props = defineProps<{
    type: LicenseKeyType;
}>();

const open = defineModel<boolean>("open", { required: true });

const onSuccess = () => {
    open.value = false;
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent class="sm:max-w-2xl">
            <DialogHeader>
                <DialogTitle>Edit License Key Type</DialogTitle>
                <DialogDescription>
                    Update the generator configuration. Changes apply to keys
                    issued from now on.
                </DialogDescription>
            </DialogHeader>

            <LicenseKeyTypeForm
                :action="`/license-keys/types/${type.uuid}`"
                method="patch"
                submit-label="Save changes"
                :initial="type"
                @success="onSuccess"
            />
        </DialogContent>
    </Dialog>
</template>
