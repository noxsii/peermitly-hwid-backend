<script setup lang="ts">
import { router, useHttp } from "@inertiajs/vue3";
import { useDebounceFn, useEventListener } from "@vueuse/core";
import { KeyRound, Loader2, Search } from "@lucide/vue";
import { ref, watch } from "vue";
import LicenseKeyStatusBadge from "@/components/license-keys/LicenseKeyStatusBadge.vue";
import { Input } from "@/components/ui/input";
import type { LicenseKeySearchResult } from "@/types";

const query = ref("");
const results = ref<LicenseKeySearchResult[]>([]);
const open = ref(false);
const root = ref<HTMLElement | null>(null);

const searchHttp = useHttp({ q: "" });

const runSearch = useDebounceFn(async (q: string) => {
    if (q.trim().length < 2) {
        results.value = [];
        return;
    }
    searchHttp.q = q;
    try {
        const response = (await searchHttp.get("/license-keys/search")) as {
            results: LicenseKeySearchResult[];
        };
        results.value = response.results;
        open.value = true;
    } catch {
        results.value = [];
    }
}, 250);

watch(query, (q) => {
    runSearch(q);
});

const pickResult = (uuid: string) => {
    open.value = false;
    query.value = "";
    results.value = [];
    router.visit(`/license-keys/${uuid}`);
};

useEventListener(document, "click", (event) => {
    if (root.value && !root.value.contains(event.target as Node)) {
        open.value = false;
    }
});
</script>

<template>
    <div ref="root" class="relative w-full">
        <Search
            class="text-muted-foreground pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2"
        />
        <Input
            v-model="query"
            type="search"
            placeholder="Search license keys…"
            aria-label="Search"
            class="bg-muted/60 h-9 rounded-full border-transparent pl-9 shadow-none"
            @focus="open = true"
        />
        <Loader2
            v-if="searchHttp.processing"
            class="text-muted-foreground absolute top-1/2 right-3 size-4 -translate-y-1/2 animate-spin"
        />

        <div
            v-if="open"
            class="bg-popover ring-foreground/10 absolute top-full left-0 z-50 mt-2 w-full overflow-hidden rounded-md shadow-lg ring-1"
        >
            <p
                v-if="query.length < 2"
                class="text-muted-foreground p-3 text-sm"
            >
                Type at least 2 characters to search license keys.
            </p>
            <p
                v-else-if="!searchHttp.processing && !results.length"
                class="text-muted-foreground p-3 text-sm"
            >
                No matches.
            </p>

            <ul v-if="results.length" class="max-h-96 overflow-y-auto">
                <li
                    v-for="item in results"
                    :key="item.uuid"
                    class="hover:bg-muted/60 cursor-pointer p-3"
                    @mousedown.prevent="pickResult(item.uuid)"
                >
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 truncate">
                            <KeyRound class="text-muted-foreground size-4" />
                            <code class="truncate font-mono text-xs">
                                {{ item.key }}
                            </code>
                        </div>
                        <LicenseKeyStatusBadge :status="item.status" />
                    </div>
                    <p class="text-muted-foreground mt-1 truncate text-xs">
                        {{ item.product ?? "—" }}
                        <span v-if="item.customer">
                            · {{ item.customer }}
                        </span>
                    </p>
                </li>
            </ul>
        </div>
    </div>
</template>
