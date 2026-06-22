<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { Loader2 } from "@lucide/vue";
import { watch } from "vue";
import InputError from "@/components/InputError.vue";
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
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Switch } from "@/components/ui/switch";
import type { CustomerOption, LicenseKey } from "@/types";

const props = defineProps<{
    licenseKey: LicenseKey;
    customers: CustomerOption[];
}>();

const open = defineModel<boolean>("open", { required: true });

const form = useForm<{
    customer_uuid: string;
    max_activations: number | null;
    requires_hwid_check: boolean;
}>({
    customer_uuid: props.licenseKey.customer?.uuid ?? "",
    max_activations: props.licenseKey.max_activations,
    requires_hwid_check: props.licenseKey.requires_hwid_check,
});

watch(
    () => props.licenseKey,
    (key) => {
        form.customer_uuid = key.customer?.uuid ?? "";
        form.max_activations = key.max_activations;
        form.requires_hwid_check = key.requires_hwid_check;
    },
);

const submit = () => {
    form.transform((data) => ({
        ...data,
        customer_uuid: data.customer_uuid || null,
        max_activations:
            data.max_activations === null ||
            data.max_activations === ("" as unknown as number)
                ? null
                : Number(data.max_activations),
    })).patch(`/license-keys/${props.licenseKey.uuid}`, {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
        },
    });
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle>Edit License Key</DialogTitle>
                <DialogDescription>
                    Adjust customer, activation limit and HWID requirement.
                    Status and validity stay untouched.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="space-y-2">
                    <Label>Customer</Label>
                    <Select v-model="form.customer_uuid">
                        <SelectTrigger
                            class="w-full"
                            :aria-invalid="!!form.errors.customer_uuid"
                        >
                            <SelectValue placeholder="No customer" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="customer in customers"
                                :key="customer.uuid"
                                :value="customer.uuid"
                            >
                                {{ customer.email }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.customer_uuid" />
                </div>

                <div class="space-y-2">
                    <Label for="max_activations">Maximum activations</Label>
                    <Input
                        id="max_activations"
                        type="number"
                        min="1"
                        v-model="form.max_activations"
                        :aria-invalid="!!form.errors.max_activations"
                        placeholder="Unlimited"
                    />
                    <InputError :message="form.errors.max_activations" />
                </div>

                <div
                    class="flex items-center justify-between rounded-md border p-3"
                >
                    <div>
                        <p class="text-sm font-medium">
                            Require Hardware ID (HWID)
                        </p>
                        <p class="text-muted-foreground text-xs">
                            External software must send a hardware ID with each
                            check.
                        </p>
                    </div>
                    <Switch v-model="form.requires_hwid_check" />
                </div>

                <DialogFooter>
                    <DialogClose as-child>
                        <Button variant="outline" type="button">Cancel</Button>
                    </DialogClose>
                    <Button type="submit" :disabled="form.processing">
                        <Loader2
                            v-if="form.processing"
                            class="size-4 animate-spin"
                        />
                        Save changes
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
