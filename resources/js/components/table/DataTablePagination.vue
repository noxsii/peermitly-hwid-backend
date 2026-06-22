<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { ChevronLeft, ChevronRight } from "@lucide/vue";
import { computed } from "vue";
import { buttonVariants } from "@/components/ui/button";
import { cn } from "@/lib/utils";
import type { PaginationMeta } from "./types";

const props = defineProps<{
    pagination: PaginationMeta;
}>();

const prevLink = computed(() => props.pagination.links[0] ?? null);
const nextLink = computed(
    () => props.pagination.links[props.pagination.links.length - 1] ?? null,
);
const pageLinks = computed(() => props.pagination.links.slice(1, -1));
</script>

<template>
    <div v-if="pagination.last_page > 1" class="space-y-2 px-4 py-3">
        <p class="text-muted-foreground text-center text-sm sm:text-left">
            Showing {{ pagination.from }} to {{ pagination.to }} of
            {{ pagination.total }} entries.
        </p>

        <div class="flex items-center justify-center gap-1 sm:justify-end">
            <Link
                v-if="prevLink"
                :href="prevLink.url ?? ''"
                :class="
                    cn(
                        buttonVariants({ variant: 'ghost', size: 'icon-sm' }),
                        !prevLink.url && 'pointer-events-none opacity-50',
                    )
                "
                preserve-scroll
                preserve-state
            >
                <ChevronLeft class="size-4" />
            </Link>

            <span class="text-muted-foreground px-3 text-sm sm:hidden">
                {{ pagination.current_page }} / {{ pagination.last_page }}
            </span>

            <template v-for="(link, i) in pageLinks" :key="i">
                <span
                    v-if="link.label === '...'"
                    class="text-muted-foreground hidden size-8 items-center justify-center text-xs sm:flex"
                >
                    ...
                </span>

                <Link
                    v-else
                    :href="link.url ?? ''"
                    :class="
                        cn(
                            buttonVariants({
                                variant: link.active ? 'outline' : 'ghost',
                                size: 'icon-sm',
                            }),
                            'hidden text-xs sm:flex',
                        )
                    "
                    preserve-scroll
                    preserve-state
                >
                    {{ link.label }}
                </Link>
            </template>

            <Link
                v-if="nextLink"
                :href="nextLink.url ?? ''"
                :class="
                    cn(
                        buttonVariants({ variant: 'ghost', size: 'icon-sm' }),
                        !nextLink.url && 'pointer-events-none opacity-50',
                    )
                "
                preserve-scroll
                preserve-state
            >
                <ChevronRight class="size-4" />
            </Link>
        </div>
    </div>
</template>
