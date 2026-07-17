<script setup lang="ts">
import AppHeader from "./AppHeader.vue";
import AppSidebar from "./AppSidebar.vue";
</script>

<template>
    <div
        class="bg-background text-foreground flex min-h-screen w-full flex-col"
    >
        <AppHeader />

        <div class="flex min-h-0 flex-1 flex-col md:flex-row">
            <AppSidebar />

            <main
                class="min-w-0 flex-1 px-5 pt-8 pb-28 sm:px-8 md:pb-12 lg:px-12 lg:pt-12"
            >
                <slot />
            </main>
        </div>
    </div>
</template>
