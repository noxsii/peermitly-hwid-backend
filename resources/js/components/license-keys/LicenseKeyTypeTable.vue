<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { DataTable } from "@/components/table";
import type { PaginationMeta } from "@/components/table";
import type { LicenseKeyType } from "@/types";
import { createLicenseKeyTypeColumns } from "./columns/licenseKeyTypeColumns";

defineProps<{
    rows: LicenseKeyType[];
    pagination?: PaginationMeta;
}>();

const destroy = (uuid: string) => {
    router.delete(`/license-keys/types/${uuid}`, { preserveScroll: true });
};

const columns = createLicenseKeyTypeColumns(destroy);
</script>

<template>
    <DataTable
        :columns="columns"
        :data="rows"
        :pagination="pagination"
        empty-text="No license key types yet. Create one to start issuing keys."
    />
</template>
