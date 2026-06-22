<script setup lang="ts">
import { Form, Head, Link, usePage } from "@inertiajs/vue3";
import { ArrowLeft, CircleCheckBig, Loader2 } from "@lucide/vue";
import { computed } from "vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

defineOptions({ layout: "" });

const page = usePage<{ flash?: { status?: string | null } }>();

const status = computed(() => page.props.flash?.status ?? null);
</script>

<template>
    <Head title="Forgot password" />

    <main
        class="bg-muted/40 flex min-h-screen items-center justify-center px-4"
    >
        <div
            class="bg-card text-card-foreground w-full max-w-md rounded-2xl border p-8 shadow-sm"
        >
            <template v-if="status">
                <div
                    class="mb-4 flex size-14 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600"
                    aria-hidden="true"
                >
                    <CircleCheckBig class="size-7" />
                </div>
                <h1 class="text-2xl font-semibold tracking-tight">
                    Check your inbox ✉️
                </h1>
                <p class="text-muted-foreground mt-2 text-sm leading-6">
                    {{ status }}
                </p>
                <p class="text-muted-foreground mt-3 text-xs">
                    Didn't get the email? Check your spam folder, or
                    <Link
                        href="/forgot-password"
                        class="text-primary font-medium hover:underline"
                    >
                        request a new link
                    </Link>
                    .
                </p>

                <Link
                    href="/login"
                    class="text-muted-foreground hover:text-foreground mt-8 inline-flex items-center gap-1 text-xs font-medium transition-colors"
                >
                    <ArrowLeft class="size-3" />
                    Back to sign in
                </Link>
            </template>

            <template v-else>
                <div class="mb-6 space-y-1">
                    <h1 class="text-2xl font-semibold tracking-tight">
                        Forgot your password? 🔑
                    </h1>
                    <p class="text-muted-foreground text-sm">
                        Enter your email and we'll send you a magic link to
                        reset it.
                    </p>
                </div>

                <Form
                    action="/forgot-password"
                    method="post"
                    class="space-y-5"
                    #default="{ errors, processing }"
                >
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

                    <Button type="submit" class="w-full" :disabled="processing">
                        <Loader2
                            v-if="processing"
                            class="size-4 animate-spin"
                        />
                        Send reset link
                    </Button>
                </Form>

                <Link
                    href="/login"
                    class="text-muted-foreground hover:text-foreground mt-6 inline-flex items-center gap-1 text-xs font-medium transition-colors"
                >
                    <ArrowLeft class="size-3" />
                    Back to sign in
                </Link>
            </template>
        </div>
    </main>
</template>
