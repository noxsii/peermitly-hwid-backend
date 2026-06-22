<script setup lang="ts">
import ApiTokensCard from "@/components/settings/ApiTokensCard.vue";
import PasskeysCard from "@/components/settings/PasskeysCard.vue";
import PasswordCard from "@/components/settings/PasswordCard.vue";
import PageLayout from "@/layout/PageLayout.vue";
import type { ApiToken, ApiTokenAbility, PasskeyRow } from "@/types";

defineProps<{
    tokens?: { data: ApiToken[] } | null;
    tokenAbilities: ApiTokenAbility[];
    passkeys?: PasskeyRow[] | null;
}>();
</script>

<template>
    <PageLayout title="Settings">
        <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-2">
            <PasswordCard />
            <div class="flex flex-col gap-4">
                <ApiTokensCard
                    :tokens="tokens"
                    :token-abilities="tokenAbilities"
                />
                <PasskeysCard :passkeys="passkeys" />
            </div>
        </div>
    </PageLayout>
</template>
