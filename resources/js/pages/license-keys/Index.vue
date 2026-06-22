<script setup lang="ts">
import { Deferred, Link, router } from "@inertiajs/vue3";
import {
    CalendarClock,
    Download,
    KeyRound,
    Plus,
    Settings2,
} from "@lucide/vue";
import { ref, watch } from "vue";
import LicenseKeyTable from "@/components/license-keys/LicenseKeyTable.vue";
import BulkCreateLicenseKeyDialog from "@/components/dialogs/BulkCreateLicenseKeyDialog.vue";
import CreateLicenseKeyDialog from "@/components/dialogs/CreateLicenseKeyDialog.vue";
import ExportLicenseKeysDialog from "@/components/dialogs/ExportLicenseKeysDialog.vue";
import ExtendLicenseKeysDialog from "@/components/dialogs/ExtendLicenseKeysDialog.vue";
import type { PaginationMeta } from "@/components/table";
import Card from "@/components/Card.vue";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Skeleton } from "@/components/ui/skeleton";
import PageLayout from "@/layout/PageLayout.vue";
import type {
    CustomerOption,
    LicenseKey,
    LicenseKeyStatus,
    LicenseKeyType,
    ProductOption,
} from "@/types";

const props = defineProps<{
    filters?: { status: LicenseKeyStatus | null };
    types?: { data: LicenseKeyType[] } | null;
    products?: { data: ProductOption[] } | null;
    customers?: { data: CustomerOption[] } | null;
    licenseKeys?: { data: LicenseKey[]; meta: PaginationMeta } | null;
}>();

const createOpen = ref(false);
const bulkOpen = ref(false);
const exportOpen = ref(false);
const extendOpen = ref(false);

const STATUS_OPTIONS: { value: LicenseKeyStatus | "all"; label: string }[] = [
    { value: "all", label: "All statuses" },
    { value: "active", label: "Active" },
    { value: "pending", label: "Pending" },
    { value: "expired", label: "Expired" },
    { value: "revoked", label: "Revoked" },
    { value: "blocked", label: "Blocked" },
];

const statusFilter = ref<LicenseKeyStatus | "all">(
    props.filters?.status ?? "all",
);

watch(statusFilter, (value) => {
    router.get("/license-keys", value === "all" ? {} : { status: value }, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
});
</script>

<template>
    <PageLayout title="License Keys">
        <template #actions>
            <Button
                variant="ghost"
                size="sm"
                aria-label="Export CSV"
                @click="exportOpen = true"
            >
                <Download class="size-4" />
                <span class="hidden sm:inline">Export CSV</span>
            </Button>
            <Button
                variant="ghost"
                size="sm"
                aria-label="Bulk create"
                @click="bulkOpen = true"
            >
                <KeyRound class="size-4" />
                <span class="hidden sm:inline">Bulk create</span>
            </Button>
            <Button
                variant="ghost"
                size="sm"
                aria-label="Extend licences"
                @click="extendOpen = true"
            >
                <CalendarClock class="size-4" />
                <span class="hidden sm:inline">Extend licences</span>
            </Button>
            <Button size="sm" aria-label="New key" @click="createOpen = true">
                <Plus class="size-4" />
                <span class="hidden sm:inline">New key</span>
            </Button>
        </template>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3 xl:items-start">
            <Card title="License Key Types" class="xl:col-span-1">
                <template #actions>
                    <Link href="/license-keys/types">
                        <Button variant="ghost" size="icon-sm">
                            <Settings2 class="size-4" />
                        </Button>
                    </Link>
                </template>
                <Deferred data="types">
                    <template #fallback>
                        <div class="space-y-2">
                            <Skeleton class="h-8 w-full" />
                            <Skeleton class="h-8 w-full" />
                            <Skeleton class="h-8 w-4/5" />
                        </div>
                    </template>
                    <ul v-if="types?.data?.length" class="space-y-1.5">
                        <li
                            v-for="type in types.data"
                            :key="type.uuid"
                            class="flex items-center justify-between text-sm"
                        >
                            <span class="truncate font-medium">
                                {{ type.name }}
                            </span>
                            <span class="text-muted-foreground text-xs">
                                {{ type.license_keys_count ?? 0 }} keys
                            </span>
                        </li>
                    </ul>
                    <p v-else class="text-muted-foreground text-sm">
                        No types yet.
                        <Link
                            href="/license-keys/types"
                            class="text-primary underline"
                        >
                            Create one
                        </Link>
                        to issue keys.
                    </p>
                </Deferred>
            </Card>

            <Card title="License Keys" class="xl:col-span-2">
                <template #actions>
                    <div class="flex w-full items-center gap-2 sm:w-auto">
                        <Label
                            class="text-muted-foreground hidden text-xs sm:inline"
                        >
                            Status
                        </Label>
                        <Select v-model="statusFilter">
                            <SelectTrigger class="h-8 w-full sm:w-36">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="option in STATUS_OPTIONS"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </template>
                <Deferred data="licenseKeys">
                    <template #fallback>
                        <div class="space-y-2">
                            <Skeleton class="h-9 w-full" />
                            <Skeleton class="h-9 w-full" />
                            <Skeleton class="h-9 w-full" />
                            <Skeleton class="h-9 w-full" />
                        </div>
                    </template>
                    <LicenseKeyTable
                        :rows="licenseKeys?.data ?? []"
                        :pagination="licenseKeys?.meta"
                    />
                </Deferred>
            </Card>
        </div>

        <CreateLicenseKeyDialog
            v-model:open="createOpen"
            :types="types?.data ?? []"
            :products="products?.data ?? []"
            :customers="customers?.data ?? []"
        />

        <BulkCreateLicenseKeyDialog
            v-model:open="bulkOpen"
            :types="types?.data ?? []"
            :products="products?.data ?? []"
            :customers="customers?.data ?? []"
        />

        <ExportLicenseKeysDialog
            v-model:open="exportOpen"
            :products="products?.data ?? []"
        />

        <ExtendLicenseKeysDialog v-model:open="extendOpen" />
    </PageLayout>
</template>
