<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { Loader2 } from "@lucide/vue";
import { computed } from "vue";
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
import {
    VALIDITY_UNITS,
    type CustomerOption,
    type LicenseKeyType,
    type LicenseValidityUnit,
    type ProductOption,
} from "@/types";

defineProps<{
    types: LicenseKeyType[];
    products: ProductOption[];
    customers: CustomerOption[];
}>();

const open = defineModel<boolean>("open", { required: true });

const form = useForm<{
    license_key_type_uuid: string;
    product_uuid: string;
    customer_uuid: string;
    count: number;
    validity_amount: number;
    validity_unit: LicenseValidityUnit;
    max_activations: number | null;
    requires_hwid_check: boolean;
}>({
    license_key_type_uuid: "",
    product_uuid: "",
    customer_uuid: "",
    count: 10,
    validity_amount: 12,
    validity_unit: "months",
    max_activations: null,
    requires_hwid_check: false,
});

const isLifetime = computed(() => form.validity_unit === "lifetime");

const submit = () => {
    form.post("/license-keys/bulk", {
        onSuccess: () => {
            open.value = false;
            form.reset();
        },
    });
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle>Bulk Create License Keys</DialogTitle>
                <DialogDescription>
                    Generate multiple license keys at once. All keys share the
                    same settings and stay pending until first API check.
                </DialogDescription>
            </DialogHeader>

            <div
                class="border-primary/30 bg-primary/5 text-foreground rounded-md border px-3 py-2 text-xs"
            >
                Bulk creation runs in the background. The dialog closes
                immediately and you'll receive a notification on the dashboard
                once all keys are ready.
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="space-y-2">
                    <Label>License key type</Label>
                    <Select v-model="form.license_key_type_uuid">
                        <SelectTrigger
                            class="w-full"
                            :aria-invalid="!!form.errors.license_key_type_uuid"
                        >
                            <SelectValue placeholder="Choose type…" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="type in types"
                                :key="type.uuid"
                                :value="type.uuid"
                            >
                                {{ type.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.license_key_type_uuid" />
                </div>

                <div class="space-y-2">
                    <Label>Product</Label>
                    <Select v-model="form.product_uuid">
                        <SelectTrigger
                            class="w-full"
                            :aria-invalid="!!form.errors.product_uuid"
                        >
                            <SelectValue placeholder="Choose product…" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="product in products"
                                :key="product.uuid"
                                :value="product.uuid"
                            >
                                {{ product.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.product_uuid" />
                </div>

                <div class="space-y-2">
                    <Label>Customer (optional)</Label>
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
                    <Label for="count">How many keys?</Label>
                    <Input
                        id="count"
                        type="number"
                        min="1"
                        max="1000"
                        v-model="form.count"
                        :aria-invalid="!!form.errors.count"
                    />
                    <p class="text-muted-foreground text-xs">
                        Maximum 1000 keys per request.
                    </p>
                    <InputError :message="form.errors.count" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-2">
                        <Label for="validity_amount">Validity</Label>
                        <Input
                            id="validity_amount"
                            type="number"
                            min="1"
                            v-model="form.validity_amount"
                            :aria-invalid="!!form.errors.validity_amount"
                            :disabled="isLifetime"
                        />
                        <InputError :message="form.errors.validity_amount" />
                    </div>
                    <div class="space-y-2">
                        <Label>Unit</Label>
                        <Select v-model="form.validity_unit">
                            <SelectTrigger class="w-full">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="unit in VALIDITY_UNITS"
                                    :key="unit.value"
                                    :value="unit.value"
                                >
                                    {{ unit.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="max_activations">
                        Maximum activations (optional)
                    </Label>
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
                            Apply HWID lock to every generated key.
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
                        Create {{ form.count }} keys
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
