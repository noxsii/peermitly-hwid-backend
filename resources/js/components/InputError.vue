<script setup lang="ts">
defineProps<{
    message?: string | null;
}>();
</script>

<template>
    <p v-if="message" class="text-destructive text-sm">
        {{ message }}
    </p>
</template>
