<script setup lang="ts">
import { Form } from "@inertiajs/vue3";
import { Loader2 } from "@lucide/vue";
import Card from "@/components/Card.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import type { OwnedTeam } from "@/types";

const props = defineProps<{
    team: OwnedTeam;
}>();
</script>

<template>
    <Card :title="props.team.name">
        <Form
            :action="`/team/${props.team.uuid}`"
            method="patch"
            class="space-y-5"
            #default="{ errors, processing }"
        >
            <div class="space-y-2">
                <Label :for="`team-name-${props.team.uuid}`">Team name</Label>
                <Input
                    :id="`team-name-${props.team.uuid}`"
                    name="name"
                    type="text"
                    :default-value="props.team.name"
                    :aria-invalid="!!errors.name || undefined"
                    autocomplete="off"
                />
                <p v-if="errors.name" class="text-destructive text-sm">
                    {{ errors.name }}
                </p>
            </div>

            <div class="pt-2">
                <Button type="submit" :disabled="processing">
                    <Loader2 v-if="processing" class="size-4 animate-spin" />
                    Save
                </Button>
            </div>
        </Form>
    </Card>
</template>
