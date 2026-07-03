<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { CircleCheck, MailCheck, TriangleAlert } from "@lucide/vue";
import { computed } from "vue";
import LogoMark from "@/components/Logo.vue";
import { Button } from "@/components/ui/button";

defineOptions({ layout: "" });

const props = defineProps<{
    state: "confirmed" | "already" | "invalid";
}>();

const content = computed(() => {
    switch (props.state) {
        case "confirmed":
            return {
                icon: CircleCheck,
                tone: "bg-emerald-500/10 text-emerald-600",
                title: "Email confirmed 🎉",
                body: "Your email is confirmed and your account is now active. You can sign in and get started.",
            };
        case "already":
            return {
                icon: MailCheck,
                tone: "bg-primary/10 text-primary",
                title: "Already confirmed",
                body: "This email was already confirmed. You can go ahead and sign in.",
            };
        default:
            return {
                icon: TriangleAlert,
                tone: "bg-amber-500/10 text-amber-600",
                title: "Link no longer valid",
                body: "This confirmation link is invalid or has expired. Sign in to request a fresh link.",
            };
    }
});
</script>

<template>
    <Head title="Email confirmation" />

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
                :class="[
                    'mx-auto flex size-14 items-center justify-center rounded-2xl',
                    content.tone,
                ]"
                aria-hidden="true"
            >
                <component :is="content.icon" class="size-7" />
            </div>

            <h1 class="mt-5 text-2xl font-semibold tracking-tight">
                {{ content.title }}
            </h1>
            <p class="text-muted-foreground mt-2 text-sm leading-6">
                {{ content.body }}
            </p>

            <Link href="/login" class="mt-6 block">
                <Button class="w-full">Go to sign in</Button>
            </Link>
        </div>
    </main>
</template>
