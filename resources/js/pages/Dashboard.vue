<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { CircleCheck, CircleSlash } from "@lucide/vue";
import { computed } from "vue";
import PageLayout from "@/layout/PageLayout.vue";
import type { PageProps } from "@/types";

const page = usePage<PageProps>();

const user = computed(() => page.props.auth?.user ?? null);
const isActive = computed(() => user.value?.is_active === true);

const greeting = computed(() => {
    const name = user.value?.name ?? "";
    const first = name.split(/\s+/)[0] ?? "";
    return first ? `Welcome back, ${first}` : "Welcome back";
});
</script>

<template>
    <PageLayout :title="greeting">
        <div class="flex min-h-[60vh] items-center justify-center">
            <div
                class="bg-card text-card-foreground border-border/70 w-full max-w-md rounded-3xl border p-8 text-center shadow-sm"
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
                        Your account is active. You have full access to
                        Peermitly.
                    </template>
                    <template v-else>
                        Your account is not active yet. An administrator needs
                        to activate it before you can use all features.
                    </template>
                </p>

                <span
                    :class="[
                        'mt-5 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium',
                        isActive
                            ? 'bg-emerald-500/10 text-emerald-600'
                            : 'bg-amber-500/10 text-amber-600',
                    ]"
                >
                    <span
                        :class="[
                            'size-1.5 rounded-full',
                            isActive ? 'bg-emerald-500' : 'bg-amber-500',
                        ]"
                    />
                    {{ isActive ? "Active" : "Pending activation" }}
                </span>
            </div>
        </div>
    </PageLayout>
</template>
