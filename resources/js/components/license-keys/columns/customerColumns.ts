import type { ColumnDef } from "@tanstack/vue-table";
import { h } from "vue";
import type { CustomerOption } from "@/types";
import CustomerRowActions from "../CustomerRowActions.vue";

export const createCustomerColumns = (
    onConfirmDelete: (uuid: string) => void,
): ColumnDef<CustomerOption>[] => [
    {
        accessorKey: "email",
        header: "Email",
        cell: ({ row }) =>
            h("span", { class: "font-medium" }, row.original.email),
    },
    {
        accessorKey: "name",
        header: "Name",
        cell: ({ row }) =>
            h(
                "span",
                { class: "text-muted-foreground" },
                row.original.name ?? "—",
            ),
    },
    {
        accessorKey: "company",
        header: "Company",
        cell: ({ row }) =>
            h(
                "span",
                { class: "text-muted-foreground" },
                row.original.company ?? "—",
            ),
    },
    {
        accessorKey: "license_keys_count",
        header: "Keys",
        cell: ({ row }) => row.original.license_keys_count ?? 0,
    },
    {
        id: "actions",
        header: "",
        cell: ({ row }) =>
            h(CustomerRowActions, {
                customer: row.original,
                onConfirmDelete,
            }),
    },
];
