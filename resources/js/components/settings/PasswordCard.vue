<script setup lang="ts">
import { Form } from "@inertiajs/vue3";
import { Loader2 } from "@lucide/vue";
import Card from "@/components/Card.vue";
import PasswordInput from "@/components/PasswordInput.vue";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
</script>

<template>
    <Card title="Password">
        <Form
            action="/settings/password"
            method="put"
            class="space-y-5"
            #default="{ errors, processing }"
        >
            <p class="text-muted-foreground text-sm">
                You'll be signed out after changing your password.
            </p>

            <div class="space-y-2">
                <Label for="current_password">Current password</Label>
                <PasswordInput
                    id="current_password"
                    name="current_password"
                    autocomplete="current-password"
                    :invalid="!!errors.current_password"
                />
                <p
                    v-if="errors.current_password"
                    class="text-destructive text-sm"
                >
                    {{ errors.current_password }}
                </p>
            </div>

            <div class="space-y-2">
                <Label for="password">New password</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    :invalid="!!errors.password"
                />
                <p v-if="errors.password" class="text-destructive text-sm">
                    {{ errors.password }}
                </p>
            </div>

            <div class="space-y-2">
                <Label for="password_confirmation">
                    Confirm new password
                </Label>
                <PasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                />
            </div>

            <div class="pt-2">
                <Button type="submit" :disabled="processing">
                    <Loader2 v-if="processing" class="size-4 animate-spin" />
                    Save password
                </Button>
            </div>
        </Form>
    </Card>
</template>
