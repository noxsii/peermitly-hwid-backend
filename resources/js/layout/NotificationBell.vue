<script setup lang="ts">
import { router, usePage, usePoll } from "@inertiajs/vue3";
import { Bell, Check, CheckCheck } from "@lucide/vue";
import { computed, ref } from "vue";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
} from "@/components/ui/sheet";
import type { NotificationsPayload } from "@/types";

const page = usePage<{ notifications: NotificationsPayload }>();

usePoll(15000, { only: ["notifications"], preserveScroll: true });

const open = ref(false);

const notifications = computed(
    () => page.props.notifications ?? { items: [], unread_count: 0 },
);

const formatDate = (value: string | null): string =>
    value ? new Date(value).toLocaleString() : "";

const titleOf = (data: Record<string, unknown>): string => {
    if (typeof data.title === "string") return data.title;
    if (typeof data.subject === "string") return data.subject;
    return "Notification";
};

const messageOf = (data: Record<string, unknown>): string | null => {
    if (typeof data.message === "string") return data.message;
    if (typeof data.body === "string") return data.body;
    return null;
};

const markRead = (id: string) => {
    router.post(
        `/notifications/${id}/read`,
        {},
        {
            preserveScroll: true,
            preserveState: true,
            only: ["notifications"],
        },
    );
};

const markAllRead = () => {
    router.post(
        "/notifications/read-all",
        {},
        {
            preserveScroll: true,
            preserveState: true,
            only: ["notifications"],
        },
    );
};
</script>

<template>
    <Sheet v-model:open="open">
        <Button
            variant="ghost"
            size="icon-sm"
            type="button"
            class="relative"
            @click="open = true"
        >
            <Bell class="size-4" />
            <Badge
                v-if="notifications.unread_count > 0"
                variant="destructive"
                class="absolute -top-1 -right-1 h-4 min-w-4 justify-center px-1 text-[10px]"
            >
                {{
                    notifications.unread_count > 99
                        ? "99+"
                        : notifications.unread_count
                }}
            </Badge>
        </Button>

        <SheetContent class="flex w-full flex-col sm:max-w-md">
            <SheetHeader>
                <SheetTitle>Notifications</SheetTitle>
                <SheetDescription>
                    {{ notifications.unread_count }} unread of
                    {{ notifications.items.length }} shown.
                </SheetDescription>
            </SheetHeader>

            <div class="flex items-center justify-end px-4">
                <Button
                    v-if="notifications.unread_count > 0"
                    variant="ghost"
                    size="sm"
                    type="button"
                    @click="markAllRead"
                >
                    <CheckCheck class="size-4" />
                    Mark all as read
                </Button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 pb-4">
                <p
                    v-if="!notifications.items.length"
                    class="text-muted-foreground py-12 text-center text-sm"
                >
                    You're all caught up.
                </p>

                <ul v-else class="divide-y">
                    <li
                        v-for="item in notifications.items"
                        :key="item.id"
                        class="flex items-start gap-3 py-3"
                        :class="
                            item.read_at
                                ? 'text-muted-foreground'
                                : 'text-foreground'
                        "
                    >
                        <span
                            class="mt-2 size-2 shrink-0 rounded-full"
                            :class="
                                item.read_at ? 'bg-transparent' : 'bg-primary'
                            "
                        />
                        <div class="min-w-0 flex-1 space-y-1">
                            <p class="text-sm font-medium">
                                {{ titleOf(item.data) }}
                            </p>
                            <p v-if="messageOf(item.data)" class="text-sm">
                                {{ messageOf(item.data) }}
                            </p>
                            <p class="text-muted-foreground text-xs">
                                {{ formatDate(item.created_at) }}
                            </p>
                        </div>
                        <Button
                            v-if="!item.read_at"
                            variant="ghost"
                            size="icon-sm"
                            type="button"
                            @click="markRead(item.id)"
                        >
                            <Check class="size-4" />
                        </Button>
                    </li>
                </ul>
            </div>
        </SheetContent>
    </Sheet>
</template>
