<script setup lang="ts">
import PasskeysCard from "@/components/settings/PasskeysCard.vue";
import PasswordCard from "@/components/settings/PasswordCard.vue";
import PageLayout from "@/layout/PageLayout.vue";
import type { PasskeyRow } from "@/types";

defineProps<{
    passkeys?: PasskeyRow[] | null;
}>();
</script>

<template>
    <PageLayout title="Settings">
        <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-2">
            <PasswordCard />
            <PasskeysCard :passkeys="passkeys" />
        </div>
    </PageLayout>
</template>
