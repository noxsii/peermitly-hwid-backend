<script setup lang="ts">
import { computed } from "vue";
import type { DashboardTeamMember } from "@/types";

const props = defineProps<{
    members: DashboardTeamMember[];
}>();

const list = computed(() =>
    props.members.map((m) => ({
        ...m,
        initials:
            m.name
                .split(/\s+/)
                .filter(Boolean)
                .slice(0, 2)
                .map((part) => part.charAt(0).toUpperCase())
                .join("") || m.email.charAt(0).toUpperCase(),
    })),
);
</script>

<template>
    <div v-if="list.length" class="space-y-3">
        <div
            v-for="member in list"
            :key="member.id"
            class="flex items-center gap-3"
        >
            <div
                class="bg-primary text-primary-foreground flex size-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold"
                aria-hidden="true"
            >
                {{ member.initials }}
            </div>
            <div class="min-w-0">
                <p class="text-foreground truncate text-sm font-medium">
                    {{ member.name }}
                </p>
                <p class="text-muted-foreground truncate text-xs">
                    {{ member.email }} · {{ member.role }}
                </p>
            </div>
        </div>
    </div>

    <p v-else class="text-muted-foreground py-4 text-center text-sm">
        No team members yet.
    </p>
</template>
