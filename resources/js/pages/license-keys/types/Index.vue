<script setup lang="ts">
import { Deferred } from "@inertiajs/vue3";
import LicenseKeyTypeForm from "@/components/license-keys/LicenseKeyTypeForm.vue";
import LicenseKeyTypeTable from "@/components/license-keys/LicenseKeyTypeTable.vue";
import type { PaginationMeta } from "@/components/table";
import Card from "@/components/Card.vue";
import { Skeleton } from "@/components/ui/skeleton";
import PageLayout from "@/layout/PageLayout.vue";
import type { LicenseKeyType } from "@/types";

defineProps<{
    types?: { data: LicenseKeyType[]; meta: PaginationMeta } | null;
}>();
</script>

<template>
    <PageLayout title="License Key Types">
        <div class="grid grid-cols-1 gap-4 xl:grid-cols-2 xl:items-start">
            <Card title="Types">
                <Deferred data="types">
                    <template #fallback>
                        <div class="space-y-2">
                            <Skeleton class="h-9 w-full" />
                            <Skeleton class="h-9 w-full" />
                            <Skeleton class="h-9 w-full" />
                        </div>
                    </template>
                    <LicenseKeyTypeTable
                        :rows="types?.data ?? []"
                        :pagination="types?.meta"
                    />
                </Deferred>
            </Card>

            <Card title="New License Key Type">
                <LicenseKeyTypeForm
                    action="/license-keys/types"
                    submit-label="Create type"
                />
            </Card>
        </div>
    </PageLayout>
</template>
