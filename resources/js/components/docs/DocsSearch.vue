<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { useMagicKeys, whenever } from "@vueuse/core";
import { FileText, Search } from "@lucide/vue";
import { computed, ref, watch } from "vue";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
} from "@/components/ui/dialog";
import { searchDocs } from "@/lib/docs";
import type { DocSection } from "@/types";

const props = defineProps<{
    sections: DocSection[];
}>();

const open = ref(false);
const query = ref("");

const results = computed(() => searchDocs(query.value, props.sections));

const keys = useMagicKeys();
whenever(keys["Meta+K"]!, () => (open.value = !open.value));
whenever(keys["Ctrl+K"]!, () => (open.value = !open.value));

watch(open, (value) => {
    if (!value) {
        query.value = "";
    }
});

function go(slug: string): void {
    open.value = false;
    router.visit(`/guide/${slug}`);
}
</script>

<template>
    <button
        type="button"
        aria-label="Search docs"
        class="border-border/60 bg-muted/40 text-muted-foreground hover:border-primary/40 hover:text-foreground flex h-9 w-9 items-center justify-center gap-2 rounded-lg border text-sm transition-colors sm:w-64 sm:justify-start sm:px-3"
        @click="open = true"
    >
        <Search class="size-4 shrink-0" />
        <span class="hidden flex-1 text-left sm:inline">Search docs…</span>
        <kbd
            class="border-border/60 bg-background text-muted-foreground hidden rounded border px-1.5 py-0.5 text-[10px] font-medium sm:inline"
        >
            ⌘K
        </kbd>
    </button>

    <Dialog v-model:open="open">
        <DialogContent class="gap-0 overflow-hidden p-0 sm:max-w-xl">
            <DialogTitle class="sr-only">Search documentation</DialogTitle>
            <DialogDescription class="sr-only">
                Search across every documentation page.
            </DialogDescription>

            <div class="border-border/60 flex items-center gap-2 border-b px-4">
                <Search class="text-muted-foreground size-4 shrink-0" />
                <input
                    v-model="query"
                    type="text"
                    placeholder="Search docs…"
                    autofocus
                    class="h-12 w-full bg-transparent text-base outline-none sm:text-sm"
                />
            </div>

            <div class="max-h-80 overflow-y-auto p-2">
                <p
                    v-if="query.length >= 2 && results.length === 0"
                    class="text-muted-foreground px-3 py-6 text-center text-sm"
                >
                    No results for “{{ query }}”.
                </p>

                <p
                    v-else-if="query.length < 2"
                    class="text-muted-foreground px-3 py-6 text-center text-sm"
                >
                    Type to search the documentation.
                </p>

                <button
                    v-for="hit in results"
                    :key="hit.slug"
                    type="button"
                    class="hover:bg-muted/60 flex w-full items-start gap-3 rounded-lg px-3 py-2.5 text-left transition-colors"
                    @click="go(hit.slug)"
                >
                    <FileText class="text-primary mt-0.5 size-4 shrink-0" />
                    <span class="min-w-0">
                        <span class="flex items-center gap-2">
                            <span class="text-sm font-medium">{{
                                hit.title
                            }}</span>
                            <span class="text-muted-foreground text-xs">
                                {{ hit.section }}
                            </span>
                        </span>
                        <span
                            class="text-muted-foreground line-clamp-1 text-xs"
                        >
                            {{ hit.snippet }}
                        </span>
                    </span>
                </button>
            </div>
        </DialogContent>
    </Dialog>
</template>
