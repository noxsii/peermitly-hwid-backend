<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import {
    CircleCheck,
    CircleSlash,
    Clock,
    KeyRound,
    ShieldCheck,
} from "@lucide/vue";
import { computed } from "vue";
import LandingDownload from "@/components/landing/LandingDownload.vue";
import { useAccess } from "@/composables/useAccess";
import PageLayout from "@/layout/PageLayout.vue";
import type { PageProps } from "@/types";

const page = usePage<PageProps & { securityCode: string | null }>();
const { isActive, subscription, hasSubscription } = useAccess();

const securityCode = computed(() => page.props.securityCode ?? "");

const greeting = computed(() => {
    const name = page.props.auth?.user?.name ?? "";
    const first = name.split(/\s+/)[0] ?? "";
    return first ? `Welcome back, ${first}` : "Welcome back";
});

const expiresOn = computed(() => {
    if (!subscription.value) return "";
    return new Date(subscription.value.ends_at).toLocaleDateString(undefined, {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});

const daysLabel = computed(() => {
    const days = subscription.value?.days_remaining ?? 0;
    return days === 1 ? "1 day left" : `${days} days left`;
});

const neverExpires = computed(
    () => subscription.value?.is_lifetime || subscription.value?.is_free,
);
</script>

<template>
    <PageLayout :title="greeting">
        <div
            class="grid w-full gap-px overflow-hidden border bg-border lg:grid-cols-2"
        >
            <section class="bg-card p-6 sm:p-8 lg:p-10">
                <p
                    class="text-muted-foreground mb-12 text-xs font-semibold tracking-[0.16em] uppercase"
                >
                    01 / Account
                </p>
                <div
                    :class="[
                        'flex size-12 items-center justify-center rounded-full',
                        isActive
                            ? 'bg-emerald-500/10 text-emerald-600'
                            : 'bg-amber-500/10 text-amber-600',
                    ]"
                    aria-hidden="true"
                >
                    <CircleCheck v-if="isActive" class="size-6" />
                    <CircleSlash v-else class="size-6" />
                </div>

                <h2 class="mt-6 text-2xl font-medium tracking-[-0.04em]">
                    {{ isActive ? "Account active" : "Account not active yet" }}
                </h2>
                <p
                    class="text-muted-foreground mt-3 max-w-sm text-sm leading-6"
                >
                    <template v-if="isActive">
                        Your account is active and ready to use.
                    </template>
                    <template v-else>
                        An administrator needs to activate your account first.
                    </template>
                </p>
            </section>

            <!-- Subscription status -->
            <section class="bg-card p-6 sm:p-8 lg:p-10">
                <p
                    class="text-muted-foreground mb-12 text-xs font-semibold tracking-[0.16em] uppercase"
                >
                    02 / Subscription
                </p>
                <div
                    :class="[
                        'flex size-12 items-center justify-center rounded-full',
                        hasSubscription
                            ? 'bg-primary/10 text-primary'
                            : 'bg-muted text-muted-foreground',
                    ]"
                    aria-hidden="true"
                >
                    <KeyRound class="size-6" />
                </div>

                <template v-if="hasSubscription">
                    <h2 class="mt-6 text-2xl font-medium tracking-[-0.04em]">
                        {{ subscription?.plan }}
                    </h2>
                    <template v-if="neverExpires">
                        <p
                            class="text-muted-foreground mt-2 flex items-center gap-1.5 text-sm"
                        >
                            <Clock class="size-4" />
                            Never expires
                        </p>
                    </template>
                    <template v-else>
                        <p
                            class="text-muted-foreground mt-2 flex items-center gap-1.5 text-sm"
                        >
                            <Clock class="size-4" />
                            {{ daysLabel }}
                        </p>
                        <p class="text-muted-foreground mt-1 text-xs">
                            Valid until {{ expiresOn }}
                        </p>
                    </template>
                    <span
                        class="bg-primary/10 text-primary mt-4 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium"
                    >
                        <span class="bg-primary size-1.5 rounded-full" />
                        Active subscription
                    </span>
                </template>

                <template v-else>
                    <h2 class="mt-6 text-2xl font-medium tracking-[-0.04em]">
                        No active subscription
                    </h2>
                    <p class="text-muted-foreground mt-2 text-sm leading-6">
                        You don't have an active plan right now. Get access to
                        start using the spoofer.
                    </p>
                </template>
            </section>

            <!-- Security code -->
            <section
                v-if="securityCode"
                class="bg-card p-6 sm:p-8 lg:col-span-2 lg:grid lg:grid-cols-2 lg:gap-12 lg:p-10"
            >
                <div>
                    <p
                        class="text-muted-foreground mb-12 text-xs font-semibold tracking-[0.16em] uppercase"
                    >
                        03 / Security
                    </p>
                    <div
                        class="bg-primary/10 text-primary flex size-12 items-center justify-center rounded-full"
                        aria-hidden="true"
                    >
                        <ShieldCheck class="size-6" />
                    </div>
                    <h2 class="mt-6 text-2xl font-medium tracking-[-0.04em]">
                        Your security code
                    </h2>
                </div>
                <div class="mt-8 flex flex-col justify-end lg:mt-0">
                    <p
                        class="font-mono text-4xl font-semibold tracking-[0.25em] sm:text-5xl"
                    >
                        {{ securityCode }}
                    </p>
                    <p
                        class="text-muted-foreground mt-5 max-w-md text-sm leading-6"
                    >
                        Keep this code private. Support will ask for it to
                        confirm your identity when you open a ticket.
                    </p>
                </div>
            </section>

            <!-- Download -->
            <LandingDownload class="bg-card lg:col-span-2" />
        </div>
    </PageLayout>
</template>
