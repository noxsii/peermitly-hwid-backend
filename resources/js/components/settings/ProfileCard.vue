<script setup lang="ts">
import { useForm, usePage } from "@inertiajs/vue3";
import { Check, Loader2 } from "@lucide/vue";
import Card from "@/components/Card.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import type { PageProps } from "@/types";

const page = usePage<PageProps>();

const form = useForm({
    name: page.props.auth.user?.name ?? "",
});

function submit(): void {
    form.put("/profile", { preserveScroll: true });
}
</script>

<template>
    <Card title="Profile">
        <form class="space-y-5" @submit.prevent="submit">
            <p class="text-muted-foreground text-sm">
                Update the name shown across your account.
            </p>

            <div class="space-y-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    v-model="form.name"
                    type="text"
                    autocomplete="name"
                    required
                    :aria-invalid="!!form.errors.name"
                />
                <p v-if="form.errors.name" class="text-destructive text-sm">
                    {{ form.errors.name }}
                </p>
            </div>

            <div class="space-y-2">
                <Label for="email">Email</Label>
                <Input
                    id="email"
                    :model-value="page.props.auth.user?.email ?? ''"
                    type="email"
                    disabled
                />
                <p class="text-muted-foreground text-xs">
                    Your email can't be changed here.
                </p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <Button type="submit" :disabled="form.processing">
                    <Loader2
                        v-if="form.processing"
                        class="size-4 animate-spin"
                    />
                    Save
                </Button>
                <span
                    v-if="form.recentlySuccessful"
                    class="flex items-center gap-1 text-sm text-emerald-600"
                >
                    <Check class="size-4" />
                    Saved
                </span>
            </div>
        </form>
    </Card>
</template>
