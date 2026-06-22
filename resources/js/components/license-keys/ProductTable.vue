<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { DataTable } from "@/components/table";
import type { PaginationMeta } from "@/components/table";
import type { ProductOption } from "@/types";
import { createProductColumns } from "./columns/productColumns";

defineProps<{
    rows: ProductOption[];
    pagination?: PaginationMeta;
}>();

const destroy = (uuid: string) => {
    router.delete(`/license-keys/products/${uuid}`, { preserveScroll: true });
};

const columns = createProductColumns(destroy);
</script>

<template>
    <DataTable
        :columns="columns"
        :data="rows"
        :pagination="pagination"
        empty-text="No products yet. Create one to start issuing keys."
    />
</template>
