<script setup lang="ts">
import AppHeader from "./AppHeader.vue";
import AppSidebar from "./AppSidebar.vue";
</script>

<template>
    <div class="bg-background text-foreground flex h-screen w-screen flex-col">
        <AppHeader />

        <div class="flex min-h-0 flex-1">
            <AppSidebar />

            <main class="flex-1 overflow-auto p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
