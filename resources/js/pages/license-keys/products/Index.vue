<script setup lang="ts">
import { Deferred } from "@inertiajs/vue3";
import ProductForm from "@/components/license-keys/ProductForm.vue";
import ProductTable from "@/components/license-keys/ProductTable.vue";
import type { PaginationMeta } from "@/components/table";
import Card from "@/components/Card.vue";
import { Skeleton } from "@/components/ui/skeleton";
import PageLayout from "@/layout/PageLayout.vue";
import type { ProductOption } from "@/types";

defineProps<{
    products?: { data: ProductOption[]; meta: PaginationMeta } | null;
}>();
</script>

<template>
    <PageLayout title="Products">
        <div class="grid grid-cols-1 gap-4 xl:grid-cols-2 xl:items-start">
            <Card title="Products">
                <Deferred data="products">
                    <template #fallback>
                        <div class="space-y-2">
                            <Skeleton class="h-9 w-full" />
                            <Skeleton class="h-9 w-full" />
                            <Skeleton class="h-9 w-full" />
                        </div>
                    </template>
                    <ProductTable
                        :rows="products?.data ?? []"
                        :pagination="products?.meta"
                    />
                </Deferred>
            </Card>

            <Card title="New Product">
                <ProductForm
                    action="/license-keys/products"
                    submit-label="Create product"
                />
            </Card>
        </div>
    </PageLayout>
</template>
