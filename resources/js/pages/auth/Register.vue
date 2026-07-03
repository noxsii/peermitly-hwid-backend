<script setup lang="ts">
import { Form, Head, Link } from "@inertiajs/vue3";
import { Eye, EyeOff, Loader2 } from "@lucide/vue";
import { ref } from "vue";
import LogoMark from "@/components/Logo.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

defineOptions({ layout: "" });

const showPassword = ref(false);
const showConfirm = ref(false);
</script>

<template>
    <Head title="Create your account" />

    <main
        class="bg-muted/40 flex min-h-screen items-center justify-center px-4 py-10"
    >
        <div
            class="bg-card text-card-foreground w-full max-w-md rounded-2xl border p-8 shadow-sm"
        >
            <Link href="/" class="mb-6 inline-flex" aria-label="Peermitly home">
                <LogoMark size="size-11" />
            </Link>
            <div class="mb-6 space-y-1">
                <h1 class="text-2xl font-semibold tracking-tight">
                    Create your account 🚀
                </h1>
                <p class="text-muted-foreground text-sm">
                    It's free to get started. We'll email you a link to confirm
                    your address.
                </p>
            </div>

            <Form
                action="/register"
                method="post"
                class="space-y-5"
                #default="{ errors, processing }"
            >
                <div class="space-y-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        autocomplete="name"
                        required
                        :aria-invalid="!!errors.name"
                    />
                    <p v-if="errors.name" class="text-destructive text-sm">
                        {{ errors.name }}
                    </p>
                </div>

                <div class="space-y-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        :aria-invalid="!!errors.email"
                    />
                    <p v-if="errors.email" class="text-destructive text-sm">
                        {{ errors.email }}
                    </p>
                </div>

                <div class="space-y-2">
                    <Label for="password">Password</Label>
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
                    <div class="relative">
                        <Input
                            id="password_confirmation"
                            name="password_confirmation"
                            :type="showConfirm ? 'text' : 'password'"
                            autocomplete="new-password"
                            required
                            class="pr-10"
                        />
                        <button
                            type="button"
                            :aria-label="
                                showConfirm ? 'Hide password' : 'Show password'
                            "
                            :aria-pressed="showConfirm"
                            class="text-muted-foreground hover:text-foreground focus-visible:ring-ring/50 absolute inset-y-0 right-0 flex items-center justify-center rounded-md px-3 outline-none focus-visible:ring-2"
                            @click="showConfirm = !showConfirm"
                        >
                            <EyeOff v-if="showConfirm" class="size-4" />
                            <Eye v-else class="size-4" />
                        </button>
                    </div>
                </div>

                <Button type="submit" class="w-full" :disabled="processing">
                    <Loader2 v-if="processing" class="size-4 animate-spin" />
                    Create account
                </Button>
            </Form>

            <p class="text-muted-foreground mt-6 text-center text-sm">
                Already have an account?
                <Link
                    href="/login"
                    class="text-primary font-medium hover:underline"
                >
                    Sign in
                </Link>
            </p>
        </div>
    </main>
</template>
