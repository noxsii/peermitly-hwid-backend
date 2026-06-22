<script setup lang="ts">
import { DataTable } from "@/components/table";
import type { PaginationMeta } from "@/components/table";
import type { LicenseKey } from "@/types";
import { licenseKeyColumns } from "./columns/licenseKeyColumns";

defineProps<{
    rows: LicenseKey[];
    pagination?: PaginationMeta;
}>();
</script>

<template>
    <DataTable
        :columns="licenseKeyColumns"
        :data="rows"
        :pagination="pagination"
        empty-text="No license keys yet."
    />
</template>
