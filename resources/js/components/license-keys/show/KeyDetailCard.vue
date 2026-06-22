<script setup lang="ts">
import { Check, Copy } from "@lucide/vue";
import { computed, ref } from "vue";
import LicenseKeyStatusBadge from "@/components/license-keys/LicenseKeyStatusBadge.vue";
import Card from "@/components/Card.vue";
import { Button } from "@/components/ui/button";
import type { LicenseKey } from "@/types";
import { formatDate } from "./formatDate";

const props = defineProps<{
    licenseKey: LicenseKey;
}>();

const copied = ref(false);

const copy = async () => {
    await navigator.clipboard.writeText(props.licenseKey.key);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
};

const validityLabel = computed(() => {
    const k = props.licenseKey;
    if (k.validity_unit === "lifetime") return "Lifetime";
    if (k.validity_amount === null) return "—";
    return `${k.validity_amount} ${k.validity_unit}`;
});

const millisRemaining = computed(() => {
    const k = props.licenseKey;
    if (k.validity_unit === "lifetime") return null;
    if (!k.expires_at) return null;
    return Math.max(0, new Date(k.expires_at).getTime() - Date.now());
});

const daysRemaining = computed(() => {
    if (millisRemaining.value === null) return null;
    return Math.ceil(millisRemaining.value / (1000 * 60 * 60 * 24));
});

const remainingLabel = computed(() => {
    const k = props.licenseKey;
    if (k.validity_unit === "lifetime") return "∞ Lifetime";
    if (millisRemaining.value === null) return "Not activated yet";
    if (millisRemaining.value === 0) return "Expired";
    if (k.validity_unit === "hours") {
        const hours = Math.ceil(millisRemaining.value / (1000 * 60 * 60));
        return `${hours} hours remaining`;
    }
    return `${daysRemaining.value} days remaining`;
});
</script>

<template>
    <Card title="Key">
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <code
                    class="bg-muted/40 flex-1 truncate rounded-md p-2 font-mono text-sm"
                >
                    {{ licenseKey.key }}
                </code>
                <Button
                    variant="outline"
                    size="icon-sm"
                    type="button"
                    @click="copy"
                >
                    <Check v-if="copied" class="size-4 text-emerald-500" />
                    <Copy v-else class="size-4" />
                </Button>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <LicenseKeyStatusBadge :status="licenseKey.status" />
                <span
                    v-if="licenseKey.requires_hwid_check"
                    class="border-border rounded-md border px-2 py-0.5 text-xs"
                >
                    HWID required
                </span>
                <span
                    v-if="licenseKey.validity_unit === 'lifetime'"
                    class="border-border rounded-md border px-2 py-0.5 text-xs"
                >
                    Lifetime
                </span>
                <span
                    class="ml-auto text-sm font-medium"
                    :class="
                        daysRemaining !== null && daysRemaining < 30
                            ? 'text-destructive'
                            : 'text-foreground'
                    "
                >
                    {{ remainingLabel }}
                </span>
            </div>

            <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm md:grid-cols-3">
                <div>
                    <dt class="text-muted-foreground text-xs">Validity</dt>
                    <dd>{{ validityLabel }}</dd>
                </div>
                <div>
                    <dt class="text-muted-foreground text-xs">Product</dt>
                    <dd>{{ licenseKey.product.name ?? "—" }}</dd>
                </div>
                <div>
                    <dt class="text-muted-foreground text-xs">Customer</dt>
                    <dd>{{ licenseKey.customer?.email ?? "—" }}</dd>
                </div>
                <div>
                    <dt class="text-muted-foreground text-xs">Type</dt>
                    <dd>{{ licenseKey.type.name ?? "—" }}</dd>
                </div>
                <div>
                    <dt class="text-muted-foreground text-xs">Activated</dt>
                    <dd>{{ formatDate(licenseKey.activated_at) }}</dd>
                </div>
                <div>
                    <dt class="text-muted-foreground text-xs">Expires</dt>
                    <dd>{{ formatDate(licenseKey.expires_at) }}</dd>
                </div>
                <div>
                    <dt class="text-muted-foreground text-xs">Checks</dt>
                    <dd>{{ licenseKey.check_count }}</dd>
                </div>
                <div>
                    <dt class="text-muted-foreground text-xs">
                        Max activations
                    </dt>
                    <dd>{{ licenseKey.max_activations ?? "Unlimited" }}</dd>
                </div>
                <div>
                    <dt class="text-muted-foreground text-xs">Activations</dt>
                    <dd>
                        <span class="tabular-nums font-medium">
                            {{ licenseKey.activations?.length ?? 0 }}
                        </span>
                        <span class="text-muted-foreground">
                            / {{ licenseKey.max_activations ?? "∞" }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </Card>
</template>
