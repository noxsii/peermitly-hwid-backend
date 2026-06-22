<script setup lang="ts">
import { Download } from "@lucide/vue";
import { computed, ref } from "vue";
import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import { Label } from "@/components/ui/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import type { LicenseKeyStatus, ProductOption } from "@/types";

const props = defineProps<{
    products: ProductOption[];
}>();

const open = defineModel<boolean>("open", { required: true });

type StatusFilter = LicenseKeyStatus | "all";
type Delimiter = "," | ";" | "tab";

const status = ref<StatusFilter>("all");
const productUuid = ref<string>("all");
const delimiter = ref<Delimiter>(",");

const statusOptions: { value: StatusFilter; label: string }[] = [
    { value: "all", label: "All statuses" },
    { value: "pending", label: "Pending" },
    { value: "active", label: "Active" },
    { value: "expired", label: "Expired" },
    { value: "revoked", label: "Revoked" },
    { value: "blocked", label: "Blocked" },
];

const delimiterOptions: { value: Delimiter; label: string }[] = [
    { value: ",", label: "Comma (,)" },
    { value: ";", label: "Semicolon (;)" },
    { value: "tab", label: "Tab" },
];

const exportUrl = computed(() => {
    const params = new URLSearchParams();
    if (status.value !== "all") params.set("status", status.value);
    if (productUuid.value !== "all")
        params.set("product_uuid", productUuid.value);
    params.set("delimiter", delimiter.value);
    return `/license-keys/export?${params.toString()}`;
});

const submit = () => {
    window.location.assign(exportUrl.value);
    open.value = false;
};

// Suppress unused props warning by exposing as computed
void props;
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle>Export License Keys</DialogTitle>
                <DialogDescription>
                    Filter the keys you want to export and pick the CSV
                    delimiter. The download starts as soon as you click Export.
                </DialogDescription>
            </DialogHeader>

            <div class="space-y-4">
                <div class="space-y-2">
                    <Label>Status</Label>
                    <Select v-model="status">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="All statuses" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="option in statusOptions"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="space-y-2">
                    <Label>Product</Label>
                    <Select v-model="productUuid">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="All products" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All products</SelectItem>
                            <SelectItem
                                v-for="product in products"
                                :key="product.uuid"
                                :value="product.uuid"
                            >
                                {{ product.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="space-y-2">
                    <Label>Delimiter</Label>
                    <Select v-model="delimiter">
                        <SelectTrigger class="w-full">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="option in delimiterOptions"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>

            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="outline" type="button">Cancel</Button>
                </DialogClose>
                <Button type="button" @click="submit">
                    <Download class="size-4" />
                    Export CSV
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
