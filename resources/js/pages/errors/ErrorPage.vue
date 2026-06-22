<script setup lang="ts">
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ArrowLeft, RefreshCcw } from "@lucide/vue";
import { computed } from "vue";
import { Button } from "@/components/ui/button";
import type { PageProps } from "@/types";

const props = defineProps<{
    status: number;
    message?: string | null;
}>();

defineOptions({ layout: "" });

const page = usePage<PageProps>();

interface ErrorMeta {
    emoji: string;
    title: string;
    description: string;
    tone: "info" | "warning" | "danger";
}

const META: Record<number, ErrorMeta> = {
    401: {
        emoji: "🔐",
        title: "Sign in to continue",
        description:
            "You need to be signed in to view this page. Hop back to the login screen.",
        tone: "warning",
    },
    403: {
        emoji: "🚫",
        title: "You're not allowed here",
        description:
            "Your account does not have permission to view this resource. If you think this is a mistake, contact a team admin.",
        tone: "warning",
    },
    404: {
        emoji: "🧭",
        title: "We can't find that page",
        description:
            "The page you were looking for has moved, was deleted, or never existed in the first place.",
        tone: "info",
    },
    419: {
        emoji: "⏳",
        title: "Your session expired",
        description:
            "For your security, the page has timed out. Refresh and try again.",
        tone: "warning",
    },
    429: {
        emoji: "🐢",
        title: "Slow down a bit",
        description:
            "You're sending requests faster than we can handle. Wait a moment and try again.",
        tone: "warning",
    },
    500: {
        emoji: "💥",
        title: "Something went sideways",
        description:
            "An unexpected error occurred on our side. We've been notified — please try again in a minute.",
        tone: "danger",
    },
    503: {
        emoji: "🛠️",
        title: "We'll be right back",
        description:
            "Peermitly is currently down for maintenance. Hang tight, we're working on it.",
        tone: "info",
    },
};

const FALLBACK: ErrorMeta = {
    emoji: "🛸",
    title: "Something unexpected happened",
    description: "An unknown error occurred. Try again or head back home.",
    tone: "danger",
};

const meta = computed<ErrorMeta>(() => META[props.status] ?? FALLBACK);

const homeHref = computed(() => (page.props.auth?.user ? "/dashboard" : "/"));

const reload = () => {
    if (typeof window !== "undefined") {
        window.location.reload();
    }
};
</script>

<template>
    <Head :title="`${status} — ${meta.title}`" />

    <main
        class="bg-background text-foreground flex min-h-screen items-center justify-center px-6 py-12"
    >
        <div class="w-full max-w-xl text-center">
            <div
                :class="[
                    'mx-auto flex size-24 items-center justify-center rounded-3xl text-5xl shadow-sm',
                    meta.tone === 'danger' && 'bg-rose-500/10',
                    meta.tone === 'warning' && 'bg-amber-500/10',
                    meta.tone === 'info' && 'bg-sky-500/10',
                ]"
                aria-hidden="true"
            >
                {{ meta.emoji }}
            </div>

            <p
                class="text-muted-foreground mt-6 font-mono text-xs tracking-[0.18em] uppercase"
            >
                Error {{ status }}
            </p>
            <h1
                class="text-foreground mt-2 text-3xl font-semibold tracking-tight md:text-4xl"
            >
                {{ meta.title }}
            </h1>
            <p
                class="text-muted-foreground mx-auto mt-3 max-w-md text-sm leading-6 md:text-base"
            >
                {{ message || meta.description }}
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <Button as-child>
                    <Link :href="homeHref">
                        <ArrowLeft class="size-4" />
                        Back to safety
                    </Link>
                </Button>
                <Button variant="outline" type="button" @click="reload">
                    <RefreshCcw class="size-4" />
                    Try again
                </Button>
            </div>

            <p class="text-muted-foreground mt-10 text-xs">
                Still stuck? Reach out to support — we'd love to help.
            </p>
        </div>
    </main>
</template>
