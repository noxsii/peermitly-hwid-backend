<script setup lang="ts">
import { useHttp } from "@inertiajs/vue3";
import { Loader2 } from "@lucide/vue";
import InputError from "@/components/InputError.vue";
import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
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
import type { ApiTokenAbility, IssuedApiToken } from "@/types";

defineProps<{
    abilities: ApiTokenAbility[];
}>();

const open = defineModel<boolean>("open", { required: true });

const emit = defineEmits<{
    created: [token: IssuedApiToken];
}>();

const form = useHttp({
    name: "",
    abilities: [] as string[],
});

const toggleAbility = (ability: string) => {
    if (form.abilities.includes(ability)) {
        form.abilities = form.abilities.filter((a) => a !== ability);
    } else {
        form.abilities = [...form.abilities, ability];
    }
};

const submit = async () => {
    try {
        const token = (await form.post(
            "/settings/api-tokens",
        )) as IssuedApiToken;
        emit("created", token);
        open.value = false;
        form.reset();
    } catch {
        // form.errors auto-populated for 422
    }
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>New API Token</DialogTitle>
                <DialogDescription>
                    Tokens authenticate external software against the Peermitly
                    API. Grant only the abilities you need.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="space-y-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        :aria-invalid="!!form.errors.name"
                        placeholder="YOUR PRODUCT (production)"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="space-y-2">
                    <Label>Abilities</Label>
                    <div class="space-y-2 rounded-md border p-3">
                        <label
                            v-for="ability in abilities"
                            :key="ability.value"
                            class="flex items-center gap-2 text-sm"
                        >
                            <Checkbox
                                :model-value="
                                    form.abilities.includes(ability.value)
                                "
                                @update:model-value="
                                    toggleAbility(ability.value)
                                "
                            />
                            <code class="font-mono text-xs">
                                {{ ability.value }}
                            </code>
                        </label>
                    </div>
                    <InputError :message="form.errors.abilities" />
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
                        Create token
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
