<script setup lang="ts" generic="T">
import type { ColumnDef } from "@tanstack/vue-table";
import { FlexRender, getCoreRowModel, useVueTable } from "@tanstack/vue-table";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import DataTablePagination from "./DataTablePagination.vue";
import type { PaginationMeta } from "./types";

const props = defineProps<{
    columns: ColumnDef<T, unknown>[];
    data: T[];
    pagination?: PaginationMeta;
    emptyText?: string;
    clickable?: boolean;
}>();

const emit = defineEmits<{
    rowClick: [row: T];
}>();

const table = useVueTable({
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
    manualPagination: true,
    manualSorting: true,
});
</script>

<template>
    <div class="w-full">
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow
                        v-for="headerGroup in table.getHeaderGroups()"
                        :key="headerGroup.id"
                    >
                        <TableHead
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                            :style="{
                                width:
                                    header.getSize() !== 150
                                        ? `${header.getSize()}px`
                                        : undefined,
                            }"
                        >
                            <FlexRender
                                v-if="!header.isPlaceholder"
                                :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows.length">
                        <TableRow
                            v-for="row in table.getRowModel().rows"
                            :key="row.id"
                            :class="{
                                'cursor-pointer hover:bg-muted/50': clickable,
                            }"
                            @click="clickable && emit('rowClick', row.original)"
                        >
                            <TableCell
                                v-for="cell in row.getVisibleCells()"
                                :key="cell.id"
                            >
                                <FlexRender
                                    :render="cell.column.columnDef.cell"
                                    :props="cell.getContext()"
                                />
                            </TableCell>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell
                                :colspan="columns.length"
                                class="text-muted-foreground h-24 text-center"
                            >
                                {{ emptyText ?? "No results." }}
                            </TableCell>
                        </TableRow>
                    </template>
                </TableBody>
            </Table>
        </div>

        <DataTablePagination v-if="pagination" :pagination="pagination" />
    </div>
</template>
