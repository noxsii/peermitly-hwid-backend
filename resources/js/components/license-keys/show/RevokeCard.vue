<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { Check } from "@lucide/vue";
import { computed, ref } from "vue";
import Card from "@/components/Card.vue";
import InputError from "@/components/InputError.vue";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import type { LicenseKey } from "@/types";

const props = defineProps<{
    licenseKey: LicenseKey;
}>();

const isRevoked = computed(() => props.licenseKey.status === "revoked");

const success = ref(false);

const form = useForm<{ reason: string }>({
    reason: props.licenseKey.revoked_reason ?? "",
});

const submit = () => {
    if (isRevoked.value) return;

    form.post(`/license-keys/${props.licenseKey.uuid}/revoke`, {
        preserveScroll: true,
        onSuccess: () => {
            success.value = true;
            setTimeout(() => (success.value = false), 3000);
        },
    });
};
</script>

<template>
    <Card title="Revoke">
        <form @submit.prevent="submit" class="space-y-3">
            <Label for="reason">Reason</Label>
            <Textarea
                id="reason"
                v-model="form.reason"
                :aria-invalid="!!form.errors.reason"
                :disabled="isRevoked"
                placeholder="Customer cancelled the subscription…"
            />
            <InputError :message="form.errors.reason" />
            <Button
                v-if="!isRevoked"
                type="submit"
                variant="destructive"
                :disabled="form.processing"
            >
                Revoke key
            </Button>
            <p v-else class="text-muted-foreground text-xs">
                This key is revoked. The reason is read-only.
            </p>
            <p
                v-if="success"
                class="flex items-center gap-1 text-xs text-emerald-500"
            >
                <Check class="size-3" />
                Key revoked.
            </p>
        </form>
    </Card>
</template>
