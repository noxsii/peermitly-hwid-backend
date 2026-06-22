import type { ColumnDef } from "@tanstack/vue-table";
import { h } from "vue";
import { Badge } from "@/components/ui/badge";
import type { LicenseKeyType } from "@/types";
import LicenseKeyTypeRowActions from "../LicenseKeyTypeRowActions.vue";

export const createLicenseKeyTypeColumns = (
    onConfirmDelete: (uuid: string) => void,
): ColumnDef<LicenseKeyType>[] => [
    {
        accessorKey: "name",
        header: "Name",
        cell: ({ row }) =>
            h("span", { class: "font-medium" }, row.original.name),
    },
    {
        accessorKey: "generator_type",
        header: "Generator",
        cell: ({ row }) =>
            h(Badge, { variant: "outline" }, () => row.original.generator_type),
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
            h(LicenseKeyTypeRowActions, {
                type: row.original,
                onConfirmDelete,
            }),
    },
];
