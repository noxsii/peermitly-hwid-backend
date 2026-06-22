import { Link } from "@inertiajs/vue3";
import { Eye } from "@lucide/vue";
import type { ColumnDef } from "@tanstack/vue-table";
import { h } from "vue";
import { Button } from "@/components/ui/button";
import type { LicenseKey } from "@/types";
import LicenseKeyStatusBadge from "../LicenseKeyStatusBadge.vue";

const formatDate = (value: string | null): string =>
    value ? new Date(value).toLocaleString() : "—";

export const licenseKeyColumns: ColumnDef<LicenseKey>[] = [
    {
        accessorKey: "key",
        header: "Key",
        cell: ({ row }) =>
            h(
                "span",
                { class: "font-mono text-xs tracking-tight" },
                row.original.key,
            ),
    },
    {
        accessorKey: "status",
        header: "Status",
        cell: ({ row }) =>
            h(LicenseKeyStatusBadge, { status: row.original.status }),
    },
    {
        accessorKey: "type.name",
        header: "Type",
        cell: ({ row }) => row.original.type.name ?? "—",
    },
    {
        accessorKey: "product.name",
        header: "Product",
        cell: ({ row }) => row.original.product.name ?? "—",
    },
    {
        accessorKey: "customer.email",
        header: "Customer",
        cell: ({ row }) => row.original.customer?.email ?? "—",
    },
    {
        accessorKey: "expires_at",
        header: "Expires",
        cell: ({ row }) => formatDate(row.original.expires_at),
    },
    {
        accessorKey: "check_count",
        header: "Checks",
        cell: ({ row }) => row.original.check_count,
    },
    {
        id: "actions",
        header: "",
        cell: ({ row }) =>
            h(
                Link,
                { href: `/license-keys/${row.original.uuid}` },
                {
                    default: () =>
                        h(
                            Button,
                            { variant: "ghost", size: "icon-sm" },
                            { default: () => h(Eye, { class: "size-4" }) },
                        ),
                },
            ),
    },
];
