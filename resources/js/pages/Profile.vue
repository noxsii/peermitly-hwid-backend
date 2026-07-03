<script setup lang="ts">
import ProfileCard from "@/components/settings/ProfileCard.vue";
import PageLayout from "@/layout/PageLayout.vue";
</script>

<template>
    <PageLayout title="Profile">
        <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-2">
            <ProfileCard />
        </div>
    </PageLayout>
</template>
