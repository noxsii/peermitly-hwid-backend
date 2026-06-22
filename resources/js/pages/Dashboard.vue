<script setup lang="ts">
import { Deferred, usePage } from "@inertiajs/vue3";
import { CircleCheck, CircleSlash, Clock, KeyRound } from "@lucide/vue";
import { computed } from "vue";
import { Skeleton } from "@/components/ui/skeleton";
import PageLayout from "@/layout/PageLayout.vue";
import type { DashboardSubscription, PageProps } from "@/types";

const props = defineProps<{
    subscription?: DashboardSubscription | null;
}>();

const page = usePage<PageProps>();

const user = computed(() => page.props.auth?.user ?? null);
const isActive = computed(() => user.value?.is_active === true);
const hasSubscription = computed(() => props.subscription != null);

const greeting = computed(() => {
    const name = user.value?.name ?? "";
    const first = name.split(/\s+/)[0] ?? "";
    return first ? `Welcome back, ${first}` : "Welcome back";
});

const expiresOn = computed(() => {
    if (!props.subscription) return "";
    return new Date(props.subscription.ends_at).toLocaleDateString(undefined, {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});

const daysLabel = computed(() => {
    const days = props.subscription?.days_remaining ?? 0;
    return days === 1 ? "1 day left" : `${days} days left`;
});
</script>

<template>
    <PageLayout :title="greeting">
        <div class="mx-auto grid w-full max-w-3xl gap-4 sm:grid-cols-2">
            <!-- Account status -->
            <div
                class="bg-card text-card-foreground border-border/70 rounded-3xl border p-8 text-center shadow-sm"
            >
                <div
                    :class="[
                        'mx-auto flex size-16 items-center justify-center rounded-2xl',
                        isActive
                            ? 'bg-emerald-500/10 text-emerald-600'
                            : 'bg-amber-500/10 text-amber-600',
                    ]"
                    aria-hidden="true"
                >
                    <CircleCheck v-if="isActive" class="size-8" />
                    <CircleSlash v-else class="size-8" />
                </div>

                <h2 class="mt-5 text-xl font-semibold tracking-tight">
                    {{ isActive ? "Account active" : "Account not active yet" }}
                </h2>
                <p class="text-muted-foreground mt-2 text-sm leading-6">
                    <template v-if="isActive">
                        Your account is active and ready to use.
                    </template>
                    <template v-else>
                        An administrator needs to activate your account first.
                    </template>
                </p>
            </div>

            <!-- Subscription status (deferred) -->
            <Deferred data="subscription">
                <template #fallback>
                    <Skeleton class="min-h-56 w-full rounded-3xl" />
                </template>

                <div
                    class="bg-card text-card-foreground border-border/70 rounded-3xl border p-8 text-center shadow-sm"
                >
                    <div
                        :class="[
                            'mx-auto flex size-16 items-center justify-center rounded-2xl',
                            hasSubscription
                                ? 'bg-primary/10 text-primary'
                                : 'bg-muted text-muted-foreground',
                        ]"
                        aria-hidden="true"
                    >
                        <KeyRound class="size-8" />
                    </div>

                    <template v-if="hasSubscription">
                        <h2 class="mt-5 text-xl font-semibold tracking-tight">
                            {{ subscription?.plan }}
                        </h2>
                        <p
                            class="text-muted-foreground mt-2 flex items-center justify-center gap-1.5 text-sm"
                        >
                            <Clock class="size-4" />
                            {{ daysLabel }}
                        </p>
                        <p class="text-muted-foreground mt-1 text-xs">
                            Valid until {{ expiresOn }}
                        </p>
                        <span
                            class="bg-primary/10 text-primary mt-4 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium"
                        >
                            <span class="bg-primary size-1.5 rounded-full" />
                            Active subscription
                        </span>
                    </template>

                    <template v-else>
                        <h2 class="mt-5 text-xl font-semibold tracking-tight">
                            No active subscription
                        </h2>
                        <p class="text-muted-foreground mt-2 text-sm leading-6">
                            You don't have an active plan right now. Get access
                            to start using the spoofer.
                        </p>
                    </template>
                </div>
            </Deferred>
        </div>
    </PageLayout>
</template>
