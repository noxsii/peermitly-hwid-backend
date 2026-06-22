<script setup lang="ts">
import { ArrowDown, ArrowUp, ArrowUpDown } from "@lucide/vue";
import { Button } from "@/components/ui/button";

defineProps<{
    title: string;
    sortable?: boolean;
    sorted?: "asc" | "desc" | false;
}>();

const emit = defineEmits<{
    sort: [];
}>();
</script>

<template>
    <div class="flex items-center gap-1">
        <Button
            v-if="sortable"
            variant="ghost"
            size="sm"
            class="hover:bg-accent -ml-3 h-8 px-2"
            @click="emit('sort')"
        >
            {{ title }}
            <ArrowUp v-if="sorted === 'asc'" class="ml-1 size-3.5" />
            <ArrowDown v-else-if="sorted === 'desc'" class="ml-1 size-3.5" />
            <ArrowUpDown v-else class="ml-1 size-3.5 opacity-50" />
        </Button>
        <span v-else>{{ title }}</span>
    </div>
</template>
