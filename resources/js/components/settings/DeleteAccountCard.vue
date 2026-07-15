<script setup lang="ts">
import { Form } from "@inertiajs/vue3";
import { Loader2 } from "@lucide/vue";
import Card from "@/components/Card.vue";
import PasswordInput from "@/components/PasswordInput.vue";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
</script>

<template>
    <Card title="Danger Zone">
        <Form
            action="/settings/account"
            method="delete"
            class="space-y-5"
            #default="{ errors, processing }"
        >
            <div class="space-y-1">
                <p class="text-foreground text-sm font-medium">
                    Delete account
                </p>
                <p class="text-muted-foreground text-sm">
                    This permanently deletes your account and all associated
                    data, including your subscription, API keys and passkeys.
                    This cannot be undone.
                </p>
            </div>

            <div class="space-y-2">
                <Label for="delete_current_password">Current password</Label>
                <PasswordInput
                    id="delete_current_password"
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

            <div class="pt-2">
                <Button
                    type="submit"
                    variant="destructive"
                    :disabled="processing"
                >
                    <Loader2 v-if="processing" class="size-4 animate-spin" />
                    Delete account
                </Button>
            </div>
        </Form>
    </Card>
</template>
