<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { Check, Copy, Loader2 } from "@lucide/vue";
import { computed, ref } from "vue";
import InputError from "@/components/InputError.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import type { CustomerOption } from "@/types";

const props = defineProps<{
    action: string;
    method?: "post" | "patch";
    submitLabel?: string;
    initial?: CustomerOption;
}>();

const emit = defineEmits<{
    success: [];
}>();

const initialMetadata = computed(() => {
    if (!props.initial?.metadata) {
        return "";
    }
    try {
        return JSON.stringify(props.initial.metadata, null, 2);
    } catch {
        return "";
    }
});

const metadataInput = ref(initialMetadata.value);
const metadataError = ref<string | null>(null);

const form = useForm<{
    email: string;
    name: string;
    company: string;
    metadata: Record<string, unknown> | null;
}>({
    email: props.initial?.email ?? "",
    name: props.initial?.name ?? "",
    company: props.initial?.company ?? "",
    metadata: props.initial?.metadata ?? null,
});

const copied = ref(false);

const copyUuid = async () => {
    if (!props.initial?.uuid) return;
    await navigator.clipboard.writeText(props.initial.uuid);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
};

const parseMetadata = (): boolean => {
    metadataError.value = null;
    const raw = metadataInput.value.trim();
    if (raw === "") {
        form.metadata = null;
        return true;
    }
    try {
        const parsed = JSON.parse(raw);
        if (
            typeof parsed !== "object" ||
            parsed === null ||
            Array.isArray(parsed)
        ) {
            metadataError.value = "Metadata must be a JSON object.";
            return false;
        }
        form.metadata = parsed as Record<string, unknown>;
        return true;
    } catch {
        metadataError.value = "Metadata must be valid JSON.";
        return false;
    }
};

const submit = () => {
    if (!parseMetadata()) {
        return;
    }
    const options = {
        onSuccess: () => {
            emit("success");
            if (props.method !== "patch") {
                form.reset();
                metadataInput.value = "";
            }
        },
    };
    if (props.method === "patch") {
        form.patch(props.action, options);
    } else {
        form.post(props.action, options);
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div v-if="initial?.uuid" class="space-y-2">
            <Label>UUID</Label>
            <div class="flex items-center gap-2">
                <code
                    class="bg-muted/40 flex-1 truncate rounded-md p-2 font-mono text-xs"
                >
                    {{ initial.uuid }}
                </code>
                <Button
                    variant="outline"
                    size="icon-sm"
                    type="button"
                    @click="copyUuid"
                >
                    <Check v-if="copied" class="size-4 text-emerald-500" />
                    <Copy v-else class="size-4" />
                </Button>
            </div>
            <p class="text-muted-foreground text-xs">
                Use this UUID as <code>customer_uuid</code> when creating keys
                via the admin API.
            </p>
        </div>

        <div class="space-y-2">
            <Label for="email">Email</Label>
            <Input
                id="email"
                v-model="form.email"
                type="email"
                :aria-invalid="!!form.errors.email"
                placeholder="ada@example.com"
            />
            <InputError :message="form.errors.email" />
        </div>

        <div class="space-y-2">
            <Label for="name">Name</Label>
            <Input
                id="name"
                v-model="form.name"
                :aria-invalid="!!form.errors.name"
                placeholder="Ada Lovelace"
            />
            <InputError :message="form.errors.name" />
        </div>

        <div class="space-y-2">
            <Label for="company">Company</Label>
            <Input
                id="company"
                v-model="form.company"
                :aria-invalid="!!form.errors.company"
                placeholder="Analytical Engines Ltd."
            />
            <InputError :message="form.errors.company" />
        </div>

        <div class="space-y-2">
            <Label for="metadata">Metadata (JSON)</Label>
            <Textarea
                id="metadata"
                v-model="metadataInput"
                :aria-invalid="!!metadataError || !!form.errors.metadata"
                placeholder='{ "plan": "enterprise", "notes": "VIP" }'
                rows="4"
                class="font-mono text-xs"
            />
            <p class="text-muted-foreground text-xs">
                Optional. Free-form JSON object stored with the customer.
            </p>
            <InputError :message="metadataError ?? form.errors.metadata" />
        </div>

        <div class="pt-2">
            <Button type="submit" :disabled="form.processing">
                <Loader2 v-if="form.processing" class="size-4 animate-spin" />
                {{ submitLabel ?? "Save customer" }}
            </Button>
        </div>
    </form>
</template>
