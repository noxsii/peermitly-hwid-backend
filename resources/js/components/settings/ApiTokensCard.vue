<script setup lang="ts">
import { Deferred, router } from "@inertiajs/vue3";
import { Plus, ShieldAlert, Trash2 } from "@lucide/vue";
import { ref } from "vue";
import Card from "@/components/Card.vue";
import ConfirmDialog from "@/components/dialogs/ConfirmDialog.vue";
import CreateApiTokenDialog from "@/components/dialogs/CreateApiTokenDialog.vue";
import ShowApiTokenDialog from "@/components/dialogs/ShowApiTokenDialog.vue";
import { useRole } from "@/composables/useRole";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Skeleton } from "@/components/ui/skeleton";
import type { ApiToken, ApiTokenAbility, IssuedApiToken } from "@/types";

const { hasRole } = useRole();
const canManageTokens = hasRole(["admin", "super_admin"]);

defineProps<{
    tokens?: { data: ApiToken[] } | null;
    tokenAbilities: ApiTokenAbility[];
}>();

const createOpen = ref(false);
const issuedToken = ref<IssuedApiToken | null>(null);
const issuedOpen = ref(false);
const deleteOpen = ref(false);
const deleteId = ref<number | null>(null);

const onTokenCreated = (token: IssuedApiToken) => {
    issuedToken.value = token;
    issuedOpen.value = true;
    router.reload({ only: ["tokens"] });
};

const formatDate = (value: string | null): string =>
    value ? new Date(value).toLocaleString() : "—";

const askDelete = (id: number) => {
    deleteId.value = id;
    deleteOpen.value = true;
};

const confirmDelete = () => {
    if (deleteId.value === null) {
        return;
    }
    router.delete(`/settings/api-tokens/${deleteId.value}`, {
        preserveScroll: true,
        onFinish: () => {
            deleteId.value = null;
        },
    });
};
</script>

<template>
    <Card title="API Tokens">
        <template v-if="canManageTokens" #actions>
            <Button variant="ghost" size="icon-sm" @click="createOpen = true">
                <Plus class="size-4" />
            </Button>
        </template>

        <div
            v-if="!canManageTokens"
            class="mb-3 flex items-start gap-2 rounded-md border border-amber-500/20 bg-amber-500/10 px-3 py-2 text-xs text-amber-700 dark:text-amber-200"
        >
            <ShieldAlert class="size-4 shrink-0" aria-hidden="true" />
            <span>
                Only admins can create or revoke API tokens. Ask a team admin if
                you need one.
            </span>
        </div>

        <Deferred data="tokens">
            <template #fallback>
                <div class="space-y-2">
                    <Skeleton class="h-9 w-full" />
                    <Skeleton class="h-9 w-full" />
                </div>
            </template>

            <p
                v-if="!tokens?.data?.length"
                class="text-muted-foreground text-sm"
            >
                No API tokens yet.
                <template v-if="canManageTokens">
                    Create one to authenticate external software.
                </template>
            </p>

            <ul v-else class="divide-y">
                <li
                    v-for="token in tokens.data"
                    :key="token.id"
                    class="flex items-center justify-between gap-3 py-2 text-sm"
                >
                    <div class="min-w-0 space-y-1">
                        <p class="truncate font-medium">{{ token.name }}</p>
                        <div class="flex flex-wrap gap-1">
                            <Badge
                                v-for="ability in token.abilities"
                                :key="ability"
                                variant="outline"
                                class="font-mono text-[10px]"
                            >
                                {{ ability }}
                            </Badge>
                        </div>
                        <p class="text-muted-foreground text-xs">
                            Last used: {{ formatDate(token.last_used_at) }}
                        </p>
                    </div>
                    <Button
                        v-if="canManageTokens"
                        variant="ghost"
                        size="icon-sm"
                        type="button"
                        @click="askDelete(token.id)"
                    >
                        <Trash2 class="text-destructive size-4" />
                    </Button>
                </li>
            </ul>
        </Deferred>

        <CreateApiTokenDialog
            v-if="canManageTokens"
            v-model:open="createOpen"
            :abilities="tokenAbilities"
            @created="onTokenCreated"
        />

        <ShowApiTokenDialog
            v-if="issuedToken"
            v-model:open="issuedOpen"
            :plain-text-token="issuedToken.plain_text_token"
        />

        <ConfirmDialog
            v-model:open="deleteOpen"
            title="Revoke API token?"
            description="External software using this token will lose access immediately."
            confirm-label="Revoke"
            destructive
            @confirm="confirmDelete"
        />
    </Card>
</template>
