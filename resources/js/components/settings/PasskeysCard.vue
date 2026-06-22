<script setup lang="ts">
import { Deferred } from "@inertiajs/vue3";
import { Fingerprint, KeyRound, Loader2, Trash2 } from "@lucide/vue";
import { ref } from "vue";
import Card from "@/components/Card.vue";
import ConfirmDialog from "@/components/dialogs/ConfirmDialog.vue";
import { usePasskeyManagement } from "@/composables/usePasskeyManagement";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Skeleton } from "@/components/ui/skeleton";
import type { PasskeyRow } from "@/types";

defineProps<{
    passkeys?: PasskeyRow[] | null;
}>();

const { passkeysSupported, processing, error, register, remove } =
    usePasskeyManagement();

const name = ref("");
const deleteOpen = ref(false);
const deleteId = ref<number | null>(null);

const onRegister = async () => {
    if (name.value.trim() === "") return;
    const ok = await register(name.value.trim());
    if (ok) name.value = "";
};

const askDelete = (id: number) => {
    deleteId.value = id;
    deleteOpen.value = true;
};

const confirmDelete = () => {
    if (deleteId.value === null) return;
    remove(deleteId.value);
    deleteId.value = null;
};

const formatDate = (value: string | null): string =>
    value ? new Date(value).toLocaleString() : "—";
</script>

<template>
    <Card title="Passkeys">
        <p
            v-if="!passkeysSupported"
            class="border-amber-500/30 bg-amber-500/10 text-amber-700 dark:text-amber-200 mb-4 flex items-start gap-2 rounded-md border px-3 py-2 text-xs"
        >
            <Fingerprint class="size-4 shrink-0" aria-hidden="true" />
            Your browser does not support passkeys. Use Chrome, Safari, or
            Firefox on a recent OS to manage passkeys.
        </p>

        <p v-if="error" class="text-destructive mb-3 text-xs">
            {{ error }}
        </p>

        <div class="space-y-2">
            <Label for="passkey_name">Add a passkey</Label>
            <div class="flex flex-col gap-2 sm:flex-row">
                <Input
                    id="passkey_name"
                    v-model="name"
                    type="text"
                    placeholder="e.g. MacBook Touch ID"
                    :disabled="!passkeysSupported || processing"
                />
                <Button
                    type="button"
                    :disabled="
                        !passkeysSupported || processing || name.trim() === ''
                    "
                    @click="onRegister"
                >
                    <Loader2 v-if="processing" class="size-4 animate-spin" />
                    <Fingerprint v-else class="size-4" />
                    Register
                </Button>
            </div>
            <p class="text-muted-foreground text-xs">
                Your browser will ask you to authenticate with Face ID, Touch
                ID, Windows Hello, or a hardware key.
            </p>
        </div>

        <div class="mt-6">
            <Deferred data="passkeys">
                <template #fallback>
                    <div class="space-y-2">
                        <Skeleton class="h-9 w-full" />
                        <Skeleton class="h-9 w-full" />
                    </div>
                </template>

                <p
                    v-if="!passkeys?.length"
                    class="text-muted-foreground text-sm"
                >
                    No passkeys yet. Register one above to sign in without a
                    password.
                </p>

                <ul v-else class="divide-y">
                    <li
                        v-for="passkey in passkeys"
                        :key="passkey.id"
                        class="flex items-center justify-between gap-3 py-2 text-sm"
                    >
                        <div class="min-w-0 space-y-0.5">
                            <p
                                class="flex items-center gap-2 truncate font-medium"
                            >
                                <KeyRound class="size-3.5 shrink-0" />
                                {{ passkey.name }}
                            </p>
                            <p class="text-muted-foreground text-xs">
                                Added {{ formatDate(passkey.created_at) }}
                                · Last used
                                {{ formatDate(passkey.last_used_at) }}
                            </p>
                        </div>
                        <Button
                            variant="ghost"
                            size="icon-sm"
                            type="button"
                            @click="askDelete(passkey.id)"
                        >
                            <Trash2 class="text-destructive size-4" />
                        </Button>
                    </li>
                </ul>
            </Deferred>
        </div>

        <ConfirmDialog
            v-model:open="deleteOpen"
            title="Remove this passkey?"
            description="You will no longer be able to sign in with this passkey on the affected device."
            confirm-label="Remove"
            destructive
            @confirm="confirmDelete"
        />
    </Card>
</template>
