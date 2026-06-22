<script setup lang="ts">
import { Form } from "@inertiajs/vue3";
import { Loader2 } from "@lucide/vue";
import { ref } from "vue";
import InputError from "@/components/InputError.vue";
import { Button } from "@/components/ui/button";
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

const props = defineProps<{
    action: string;
    method?: "post" | "patch" | "put";
    submitLabel?: string;
    bulk?: boolean;
    types: LicenseKeyType[];
    products: ProductOption[];
    customers: CustomerOption[];
    initial?: {
        license_key_type_uuid?: string | null;
        product_uuid?: string | null;
        customer_uuid?: string | null;
        validity_amount?: number | null;
        validity_unit?: string;
        max_activations?: number | null;
        requires_hwid_check?: boolean;
        count?: number;
    };
}>();

const typeUuid = ref<string>(props.initial?.license_key_type_uuid ?? "");
const productUuid = ref<string>(props.initial?.product_uuid ?? "");
const customerUuid = ref<string>(props.initial?.customer_uuid ?? "");
const validityUnit = ref<LicenseValidityUnit>(
    (props.initial?.validity_unit as LicenseValidityUnit) ?? "months",
);
</script>

<template>
    <Form
        :action="action"
        :method="method ?? 'post'"
        class="space-y-5"
        #default="{ errors, processing }"
    >
        <div class="space-y-2">
            <Label>License key type</Label>
            <Select v-model="typeUuid" name="license_key_type_uuid">
                <SelectTrigger
                    class="w-full"
                    :aria-invalid="!!errors.license_key_type_uuid"
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
            <InputError :message="errors.license_key_type_uuid" />
        </div>

        <div class="space-y-2">
            <Label>Product</Label>
            <Select v-model="productUuid" name="product_uuid">
                <SelectTrigger
                    class="w-full"
                    :aria-invalid="!!errors.product_uuid"
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
            <InputError :message="errors.product_uuid" />
        </div>

        <div class="space-y-2">
            <Label>Customer (optional)</Label>
            <Select v-model="customerUuid" name="customer_uuid">
                <SelectTrigger
                    class="w-full"
                    :aria-invalid="!!errors.customer_uuid"
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
            <InputError :message="errors.customer_uuid" />
        </div>

        <div v-if="bulk" class="space-y-2">
            <Label for="count">How many keys?</Label>
            <Input
                id="count"
                name="count"
                type="number"
                min="1"
                max="1000"
                :aria-invalid="!!errors.count"
                :default-value="initial?.count ?? 10"
            />
            <InputError :message="errors.count" />
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-2">
                <Label for="validity_amount">Validity</Label>
                <Input
                    id="validity_amount"
                    name="validity_amount"
                    type="number"
                    min="1"
                    :aria-invalid="!!errors.validity_amount"
                    :default-value="initial?.validity_amount ?? 12"
                    :disabled="validityUnit === 'lifetime'"
                />
                <InputError :message="errors.validity_amount" />
            </div>
            <div class="space-y-2">
                <Label>Unit</Label>
                <Select v-model="validityUnit" name="validity_unit">
                    <SelectTrigger
                        class="w-full"
                        :aria-invalid="!!errors.validity_unit"
                    >
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
                <InputError :message="errors.validity_unit" />
            </div>
        </div>

        <div class="space-y-2">
            <Label for="max_activations">
                Maximum activations (optional)
            </Label>
            <Input
                id="max_activations"
                name="max_activations"
                type="number"
                min="1"
                :aria-invalid="!!errors.max_activations"
                :default-value="initial?.max_activations ?? undefined"
                placeholder="Unlimited"
            />
            <InputError :message="errors.max_activations" />
        </div>

        <div class="flex items-center justify-between rounded-md border p-3">
            <div>
                <p class="text-sm font-medium">Require Hardware ID (HWID)</p>
                <p class="text-muted-foreground text-xs">
                    External software must send a hardware ID with each check.
                </p>
            </div>
            <Switch
                name="requires_hwid_check"
                value="1"
                :default-value="initial?.requires_hwid_check ?? false"
            />
        </div>

        <div class="pt-2">
            <Button type="submit" :disabled="processing">
                <Loader2 v-if="processing" class="size-4 animate-spin" />
                {{ submitLabel ?? "Create key" }}
            </Button>
        </div>
    </Form>
</template>
