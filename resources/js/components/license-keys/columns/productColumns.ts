import type { ColumnDef } from "@tanstack/vue-table";
import { h } from "vue";
import { Badge } from "@/components/ui/badge";
import type { ProductOption } from "@/types";
import ProductRowActions from "../ProductRowActions.vue";

export const createProductColumns = (
    onConfirmDelete: (uuid: string) => void,
): ColumnDef<ProductOption>[] => [
    {
        accessorKey: "name",
        header: "Name",
        cell: ({ row }) =>
            h("span", { class: "font-medium" }, row.original.name),
    },
    {
        accessorKey: "slug",
        header: "Slug",
        cell: ({ row }) =>
            h("code", { class: "font-mono text-xs" }, row.original.slug),
    },
    {
        accessorKey: "license_keys_count",
        header: "Keys",
        cell: ({ row }) => row.original.license_keys_count ?? 0,
    },
    {
        accessorKey: "is_active",
        header: "Active",
        cell: ({ row }) =>
            h(
                Badge,
                {
                    variant: row.original.is_active ? "success" : "default",
                },
                () => (row.original.is_active ? "Yes" : "No"),
            ),
    },
    {
        id: "actions",
        header: "",
        cell: ({ row }) =>
            h(ProductRowActions, {
                product: row.original,
                onConfirmDelete,
            }),
    },
];
