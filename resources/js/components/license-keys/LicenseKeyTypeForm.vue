<script setup lang="ts">
import { useForm, useHttp } from "@inertiajs/vue3";
import { Check, Copy, Loader2, Sparkles } from "@lucide/vue";
import { computed, ref, watch } from "vue";
import InputError from "@/components/InputError.vue";
import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
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
import { Textarea } from "@/components/ui/textarea";
import type { LicenseKeyGeneratorType, LicenseKeyType } from "@/types";

const props = defineProps<{
    action: string;
    method?: "post" | "patch";
    submitLabel?: string;
    initial?: LicenseKeyType;
}>();

const emit = defineEmits<{
    success: [];
}>();

type RandomConfig = {
    length: number;
    group_length: number;
    separator: string;
    prefix: string;
    uppercase: boolean;
    lowercase: boolean;
    numbers: boolean;
    exclude_ambiguous_characters: boolean;
};

type UuidConfig = {
    uuid_version: "v4" | "v7";
    with_hyphens: boolean;
    uppercase: boolean;
    prefix: string;
};

type PatternConfig = {
    pattern: string;
    exclude_ambiguous_characters: boolean;
};

const seedRandom = (): RandomConfig => ({
    length: 12,
    group_length: 4,
    separator: "-",
    prefix: "LIC",
    uppercase: true,
    lowercase: false,
    numbers: true,
    exclude_ambiguous_characters: true,
    ...(props.initial?.generator_type === "random"
        ? (props.initial.configuration as Partial<RandomConfig>)
        : {}),
});

const seedUuid = (): UuidConfig => ({
    uuid_version: "v4",
    with_hyphens: true,
    uppercase: false,
    prefix: "",
    ...(props.initial?.generator_type === "uuid"
        ? (props.initial.configuration as Partial<UuidConfig>)
        : {}),
});

const seedPattern = (): PatternConfig => ({
    pattern: "LIC-{XXXX}-{XXXX}-{XXXX}",
    exclude_ambiguous_characters: true,
    ...(props.initial?.generator_type === "pattern"
        ? (props.initial.configuration as Partial<PatternConfig>)
        : {}),
});

const randomConfig = ref<RandomConfig>(seedRandom());
const uuidConfig = ref<UuidConfig>(seedUuid());
const patternConfig = ref<PatternConfig>(seedPattern());

const currentConfig = computed(() => {
    if (form.generator_type === "uuid") return uuidConfig.value;
    if (form.generator_type === "pattern") return patternConfig.value;
    return randomConfig.value;
});

const form = useForm<{
    name: string;
    description: string;
    generator_type: LicenseKeyGeneratorType;
    is_active: boolean;
    configuration: RandomConfig | UuidConfig | PatternConfig;
}>({
    name: props.initial?.name ?? "",
    description: props.initial?.description ?? "",
    generator_type: props.initial?.generator_type ?? "random",
    is_active: props.initial?.is_active ?? true,
    configuration: seedRandom(),
});

watch(currentConfig, (value) => {
    form.configuration = value;
});

const submit = () => {
    form.configuration = currentConfig.value;
    const options = {
        onSuccess: () => {
            emit("success");
        },
    };
    if (props.method === "patch") {
        form.patch(props.action, options);
    } else {
        form.post(props.action, options);
    }
};

const samples = ref<string[]>([]);
const previewError = ref<string | null>(null);

const uuidCopied = ref(false);

const copyUuid = async () => {
    if (!props.initial?.uuid) return;
    await navigator.clipboard.writeText(props.initial.uuid);
    uuidCopied.value = true;
    setTimeout(() => (uuidCopied.value = false), 2000);
};

const previewForm = useHttp({
    generator_type: "random" as LicenseKeyGeneratorType,
    configuration: {} as Record<string, unknown>,
});

const preview = async () => {
    previewError.value = null;
    previewForm.generator_type = form.generator_type;
    previewForm.configuration = currentConfig.value as Record<string, unknown>;

    try {
        const response = (await previewForm.post(
            "/license-keys/types/preview",
        )) as { samples: string[] };
        samples.value = response.samples;
    } catch (err) {
        previewError.value = err instanceof Error ? err.message : "Failed";
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-5">
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
                    <Check v-if="uuidCopied" class="size-4 text-emerald-500" />
                    <Copy v-else class="size-4" />
                </Button>
            </div>
            <p class="text-muted-foreground text-xs">
                Use this UUID as <code>license_key_type_uuid</code> when
                creating keys via the admin API.
            </p>
        </div>

        <div class="space-y-2">
            <Label for="name">Name</Label>
            <Input
                id="name"
                v-model="form.name"
                :aria-invalid="!!form.errors.name"
                placeholder="Standard License"
            />
            <InputError :message="form.errors.name" />
        </div>

        <div class="space-y-2">
            <Label for="description">Description</Label>
            <Textarea
                id="description"
                v-model="form.description"
                :aria-invalid="!!form.errors.description"
                placeholder="What is this key format used for?"
            />
            <InputError :message="form.errors.description" />
        </div>

        <div class="space-y-2">
            <Label>Generator</Label>
            <Select v-model="form.generator_type">
                <SelectTrigger
                    class="w-full"
                    :aria-invalid="!!form.errors.generator_type"
                >
                    <SelectValue />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="random">Random Characters</SelectItem>
                    <SelectItem value="uuid">UUID</SelectItem>
                    <SelectItem value="pattern">Pattern Based</SelectItem>
                </SelectContent>
            </Select>
            <InputError :message="form.errors.generator_type" />
        </div>

        <div
            v-if="form.generator_type === 'random'"
            class="space-y-3 rounded-md border p-3"
        >
            <p class="text-muted-foreground text-xs uppercase">Random config</p>
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-2">
                    <Label>Total length</Label>
                    <Input
                        type="number"
                        min="4"
                        max="64"
                        v-model="randomConfig.length"
                    />
                </div>
                <div class="space-y-2">
                    <Label>Group length</Label>
                    <Input
                        type="number"
                        min="0"
                        max="16"
                        v-model="randomConfig.group_length"
                    />
                </div>
                <div class="space-y-2">
                    <Label>Separator</Label>
                    <Input v-model="randomConfig.separator" />
                </div>
                <div class="space-y-2">
                    <Label>Prefix</Label>
                    <Input v-model="randomConfig.prefix" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <label class="flex items-center gap-2">
                    <Checkbox v-model="randomConfig.uppercase" /> Uppercase
                </label>
                <label class="flex items-center gap-2">
                    <Checkbox v-model="randomConfig.lowercase" /> Lowercase
                </label>
                <label class="flex items-center gap-2">
                    <Checkbox v-model="randomConfig.numbers" /> Numbers
                </label>
                <label class="flex items-center gap-2">
                    <Checkbox
                        v-model="randomConfig.exclude_ambiguous_characters"
                    />
                    Exclude ambiguous
                </label>
            </div>
        </div>

        <div
            v-if="form.generator_type === 'uuid'"
            class="space-y-3 rounded-md border p-3"
        >
            <p class="text-muted-foreground text-xs uppercase">UUID config</p>
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-2">
                    <Label>Version</Label>
                    <Select v-model="uuidConfig.uuid_version">
                        <SelectTrigger class="w-full">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="v4">v4 (random)</SelectItem>
                            <SelectItem value="v7">
                                v7 (time-sortable)
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="space-y-2">
                    <Label>Prefix</Label>
                    <Input v-model="uuidConfig.prefix" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <label class="flex items-center gap-2">
                    <Checkbox v-model="uuidConfig.with_hyphens" /> With hyphens
                </label>
                <label class="flex items-center gap-2">
                    <Checkbox v-model="uuidConfig.uppercase" /> Uppercase
                </label>
            </div>
        </div>

        <div
            v-if="form.generator_type === 'pattern'"
            class="space-y-3 rounded-md border p-3"
        >
            <p class="text-muted-foreground text-xs uppercase">
                Pattern config
            </p>
            <div class="space-y-2">
                <Label>Pattern</Label>
                <Input v-model="patternConfig.pattern" />
                <p class="text-muted-foreground text-xs">
                    Placeholders: {A}, {a}, {9}, {X}, {x}, {uuid}, {year},
                    {month}, {product}, {customer}
                </p>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <Checkbox
                    v-model="patternConfig.exclude_ambiguous_characters"
                />
                Exclude ambiguous
            </label>
        </div>

        <div class="flex items-center justify-between rounded-md border p-3">
            <p class="text-sm font-medium">Active</p>
            <Switch v-model="form.is_active" />
        </div>

        <div class="space-y-2">
            <Button type="button" variant="outline" @click="preview">
                <Loader2
                    v-if="previewForm.processing"
                    class="size-4 animate-spin"
                />
                <Sparkles v-else class="size-4" />
                Preview 3 samples
            </Button>
            <div
                v-if="samples.length"
                class="bg-muted/40 space-y-1 rounded-md p-3 font-mono text-xs"
            >
                <p v-for="(sample, i) in samples" :key="i">{{ sample }}</p>
            </div>
            <p v-if="previewError" class="text-destructive text-xs">
                {{ previewError }}
            </p>
        </div>

        <InputError :message="form.errors.configuration" />

        <div class="pt-2">
            <Button type="submit" :disabled="form.processing">
                <Loader2 v-if="form.processing" class="size-4 animate-spin" />
                {{ submitLabel ?? "Save type" }}
            </Button>
        </div>
    </form>
</template>
