<script setup lang="ts">
import type { HTMLAttributes } from "vue";
import { cn } from "@/lib/utils";

const props = defineProps<{ class?: HTMLAttributes["class"] }>();
</script>

<template>
    <th
        data-slot="table-head"
        :class="
            cn(
                'text-muted-foreground h-9 px-2 text-left align-middle text-xs font-medium tracking-wide uppercase',
                props.class,
            )
        "
    >
        <slot />
    </th>
</template>
