<script setup lang="ts">
import Card from "@/components/Card.vue";
import type { LicenseKey } from "@/types";
import { formatDate } from "./formatDate";

const props = defineProps<{
    licenseKey: LicenseKey;
}>();

void props;
</script>

<template>
    <Card v-if="licenseKey.activations?.length" title="Hardware IDs">
        <ul class="divide-y">
            <li
                v-for="activation in licenseKey.activations"
                :key="activation.uuid"
                class="flex items-center justify-between gap-3 py-2 text-sm"
            >
                <div class="min-w-0 space-y-0.5">
                    <code class="block truncate font-mono text-xs">
                        {{ activation.machine_id }}
                    </code>
                    <p class="text-muted-foreground text-xs">
                        <span v-if="activation.hostname">
                            {{ activation.hostname }}
                        </span>
                        <span v-if="activation.ip_address">
                            · {{ activation.ip_address }}
                        </span>
                    </p>
                </div>
                <div class="text-muted-foreground shrink-0 text-right text-xs">
                    <p>First: {{ formatDate(activation.activated_at) }}</p>
                    <p>Last: {{ formatDate(activation.last_seen_at) }}</p>
                </div>
            </li>
        </ul>
    </Card>

    <Card v-else title="Hardware IDs">
        <p class="text-muted-foreground text-sm">
            No hardware IDs registered yet. The first successful API check will
            register one.
        </p>
    </Card>
</template>
