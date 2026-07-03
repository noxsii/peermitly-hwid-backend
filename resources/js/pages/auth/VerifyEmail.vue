<script setup lang="ts">
import { Form, Head, Link } from "@inertiajs/vue3";
import { Loader2, MailCheck } from "@lucide/vue";
import LogoMark from "@/components/Logo.vue";
import { Button } from "@/components/ui/button";

defineOptions({ layout: "" });

defineProps<{
    status?: string | null;
}>();
</script>

<template>
    <Head title="Confirm your email" />

    <main
        class="bg-muted/40 flex min-h-screen items-center justify-center px-4 py-10"
    >
        <div
            class="bg-card text-card-foreground w-full max-w-md rounded-2xl border p-8 text-center shadow-sm"
        >
            <Link href="/" class="mb-6 inline-flex" aria-label="Peermitly home">
                <LogoMark size="size-11" />
            </Link>

            <div
                class="bg-primary/10 text-primary mx-auto flex size-14 items-center justify-center rounded-2xl"
                aria-hidden="true"
            >
                <MailCheck class="size-7" />
            </div>

            <h1 class="mt-5 text-2xl font-semibold tracking-tight">
                Confirm your email
            </h1>
            <p class="text-muted-foreground mt-2 text-sm leading-6">
                We've sent a confirmation link to your inbox. Click it to
                activate your account — the link is valid for 3 hours. Didn't
                get it? Request a fresh one below.
            </p>

            <p
                v-if="status"
                class="mt-4 rounded-lg bg-emerald-500/10 px-3 py-2 text-sm text-emerald-600"
            >
                {{ status }}
            </p>

            <Form
                action="/email/verification-notification"
                method="post"
                class="mt-6"
                #default="{ processing }"
            >
                <Button type="submit" class="w-full" :disabled="processing">
                    <Loader2 v-if="processing" class="size-4 animate-spin" />
                    Resend confirmation link
                </Button>
            </Form>

            <Form action="/logout" method="post" class="mt-3">
                <button
                    type="submit"
                    class="text-muted-foreground hover:text-foreground text-sm"
                >
                    Sign out
                </button>
            </Form>
        </div>
    </main>
</template>
