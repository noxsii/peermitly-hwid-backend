<script setup lang="ts">
import { Deferred, Link, router } from "@inertiajs/vue3";
import { ArrowLeft } from "@lucide/vue";
import { ref } from "vue";
import ConfirmDialog from "@/components/dialogs/ConfirmDialog.vue";
import EditLicenseKeyDialog from "@/components/dialogs/EditLicenseKeyDialog.vue";
import ActionsCard from "@/components/license-keys/show/ActionsCard.vue";
import ExtendCard from "@/components/license-keys/show/ExtendCard.vue";
import HardwareIdsCard from "@/components/license-keys/show/HardwareIdsCard.vue";
import KeyDetailCard from "@/components/license-keys/show/KeyDetailCard.vue";
import RevokeCard from "@/components/license-keys/show/RevokeCard.vue";
import { Button } from "@/components/ui/button";
import { Skeleton } from "@/components/ui/skeleton";
import PageLayout from "@/layout/PageLayout.vue";
import type { CustomerOption, LicenseKey } from "@/types";

const props = defineProps<{
    licenseKey?: { data: LicenseKey } | null;
    customers?: { data: CustomerOption[] } | null;
}>();

const editOpen = ref(false);
const deleteOpen = ref(false);

const confirmDelete = () => {
    if (!props.licenseKey?.data) return;
    router.delete(`/license-keys/${props.licenseKey.data.uuid}`);
};
</script>

<template>
    <PageLayout title="License Key">
        <template #actions>
            <Link href="/license-keys">
                <Button variant="ghost" size="sm">
                    <ArrowLeft class="size-4" />
                    Back
                </Button>
            </Link>
        </template>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:items-start">
            <div class="space-y-4 lg:col-span-2">
                <Deferred data="licenseKey">
                    <template #fallback>
                        <div class="space-y-3 rounded-2xl p-4">
                            <Skeleton class="h-10 w-full" />
                            <Skeleton class="h-6 w-1/3" />
                        </div>
                    </template>

                    <template v-if="licenseKey?.data">
                        <KeyDetailCard :license-key="licenseKey.data" />

                        <HardwareIdsCard
                            v-if="licenseKey.data.requires_hwid_check"
                            :license-key="licenseKey.data"
                        />
                    </template>
                </Deferred>
            </div>

            <div v-if="licenseKey?.data" class="space-y-4">
                <RevokeCard :license-key="licenseKey.data" />

                <ExtendCard
                    :uuid="licenseKey.data.uuid"
                    :expires-at="licenseKey.data.expires_at"
                />

                <ActionsCard
                    :license-key="licenseKey.data"
                    @edit="editOpen = true"
                    @delete="deleteOpen = true"
                />
            </div>
        </div>

        <EditLicenseKeyDialog
            v-if="licenseKey?.data"
            v-model:open="editOpen"
            :license-key="licenseKey.data"
            :customers="customers?.data ?? []"
        />

        <ConfirmDialog
            v-if="licenseKey?.data"
            v-model:open="deleteOpen"
            title="Delete license key?"
            :description="`This will permanently remove ${licenseKey.data.key}. External software using this key will lose access immediately. This cannot be undone.`"
            confirm-label="Delete"
            destructive
            @confirm="confirmDelete"
        />
    </PageLayout>
</template>
