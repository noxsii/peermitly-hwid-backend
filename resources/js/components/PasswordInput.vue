<script setup lang="ts">
import { Eye, EyeOff } from "@lucide/vue";
import { ref } from "vue";
import { Input } from "@/components/ui/input";

defineProps<{
    id: string;
    name: string;
    autocomplete?: string;
    invalid?: boolean;
}>();

const visible = ref(false);
</script>

<template>
    <div class="relative">
        <Input
            :id="id"
            :name="name"
            :type="visible ? 'text' : 'password'"
            :autocomplete="autocomplete"
            required
            class="pr-10"
            :aria-invalid="invalid"
        />
        <button
            type="button"
            :aria-label="visible ? 'Hide password' : 'Show password'"
            :aria-pressed="visible"
            class="text-muted-foreground hover:text-foreground focus-visible:ring-ring/50 absolute inset-y-0 right-0 flex items-center justify-center rounded-md px-3 outline-none focus-visible:ring-2"
            @click="visible = !visible"
        >
            <EyeOff v-if="visible" class="size-4" />
            <Eye v-else class="size-4" />
        </button>
    </div>
</template>
