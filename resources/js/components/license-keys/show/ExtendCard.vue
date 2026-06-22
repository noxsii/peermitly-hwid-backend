<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { Check } from "@lucide/vue";
import { ref } from "vue";
import Card from "@/components/Card.vue";
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
import { VALIDITY_UNITS, type LicenseValidityUnit } from "@/types";
import { formatDate } from "./formatDate";

const props = defineProps<{
    uuid: string;
    expiresAt: string | null;
}>();

const success = ref(false);

const form = useForm<{
    amount: number;
    unit: LicenseValidityUnit;
}>({
    amount: 12,
    unit: "months",
});

const submit = () => {
    form.post(`/license-keys/${props.uuid}/extend`, {
        preserveScroll: true,
        onSuccess: () => {
            success.value = true;
            setTimeout(() => (success.value = false), 3000);
        },
    });
};
</script>

<template>
    <Card title="Extend">
        <form @submit.prevent="submit" class="space-y-3">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <Label for="amount">Amount</Label>
                    <Input
                        id="amount"
                        type="number"
                        min="1"
                        v-model="form.amount"
                        :aria-invalid="!!form.errors.amount"
                    />
                    <InputError :message="form.errors.amount" />
                </div>
                <div>
                    <Label>Unit</Label>
                    <Select v-model="form.unit">
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
            <Button type="submit" :disabled="form.processing">Extend</Button>
            <p
                v-if="success"
                class="flex items-center gap-1 text-xs text-emerald-500"
            >
                <Check class="size-3" />
                Extended. New expiry: {{ formatDate(expiresAt) }}
            </p>
        </form>
    </Card>
</template>
