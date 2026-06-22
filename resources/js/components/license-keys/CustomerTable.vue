<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { DataTable } from "@/components/table";
import type { PaginationMeta } from "@/components/table";
import type { CustomerOption } from "@/types";
import { createCustomerColumns } from "./columns/customerColumns";

defineProps<{
    rows: CustomerOption[];
    pagination?: PaginationMeta;
}>();

const destroy = (uuid: string) => {
    router.delete(`/license-keys/customers/${uuid}`, { preserveScroll: true });
};

const columns = createCustomerColumns(destroy);
</script>

<template>
    <DataTable
        :columns="columns"
        :data="rows"
        :pagination="pagination"
        empty-text="No customers yet. Create one to attach license keys."
    />
</template>
