<script setup lang="ts">
import { Form, Head, Link } from "@inertiajs/vue3";
import { ArrowLeft, Eye, EyeOff, Loader2 } from "@lucide/vue";
import { ref } from "vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

const props = defineProps<{
    token: string;
    email: string;
}>();

defineOptions({ layout: "" });

const showPassword = ref(false);
</script>

<template>
    <Head title="Reset password" />

    <main
        class="bg-muted/40 flex min-h-screen items-center justify-center px-4"
    >
        <div
            class="bg-card text-card-foreground w-full max-w-md rounded-2xl border p-8 shadow-sm"
        >
            <div class="mb-6 space-y-1">
                <h1 class="text-2xl font-semibold tracking-tight">
                    Choose a new password 🔒
                </h1>
                <p class="text-muted-foreground text-sm">
                    Set a strong password for your Peermitly account.
                </p>
            </div>

            <Form
                action="/reset-password"
                method="post"
                class="space-y-5"
                #default="{ errors, processing }"
            >
                <input type="hidden" name="token" :value="props.token" />

                <div class="space-y-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        :default-value="props.email"
                        :aria-invalid="!!errors.email"
                    />
                    <p v-if="errors.email" class="text-destructive text-sm">
                        {{ errors.email }}
                    </p>
                </div>

                <div class="space-y-2">
                    <Label for="password">New password</Label>
                    <div class="relative">
                        <Input
                            id="password"
                            name="password"
                            :type="showPassword ? 'text' : 'password'"
                            autocomplete="new-password"
                            required
                            class="pr-10"
                            :aria-invalid="!!errors.password"
                        />
                        <button
                            type="button"
                            :aria-label="
                                showPassword ? 'Hide password' : 'Show password'
                            "
                            :aria-pressed="showPassword"
                            class="text-muted-foreground hover:text-foreground focus-visible:ring-ring/50 absolute inset-y-0 right-0 flex items-center justify-center rounded-md px-3 outline-none focus-visible:ring-2"
                            @click="showPassword = !showPassword"
                        >
                            <EyeOff v-if="showPassword" class="size-4" />
                            <Eye v-else class="size-4" />
                        </button>
                    </div>
                    <p v-if="errors.password" class="text-destructive text-sm">
                        {{ errors.password }}
                    </p>
                </div>

                <div class="space-y-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input
                        id="password_confirmation"
                        name="password_confirmation"
                        :type="showPassword ? 'text' : 'password'"
                        autocomplete="new-password"
                        required
                    />
                </div>

                <Button type="submit" class="w-full" :disabled="processing">
                    <Loader2 v-if="processing" class="size-4 animate-spin" />
                    Reset password
                </Button>
            </Form>

            <Link
                href="/login"
                class="text-muted-foreground hover:text-foreground mt-6 inline-flex items-center gap-1 text-xs font-medium transition-colors"
            >
                <ArrowLeft class="size-3" />
                Back to sign in
            </Link>
        </div>
    </main>
</template>
