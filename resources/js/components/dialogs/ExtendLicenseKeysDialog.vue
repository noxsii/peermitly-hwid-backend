<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { CalendarClock, Loader2 } from "@lucide/vue";
import { computed } from "vue";
import InputError from "@/components/InputError.vue";
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
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { type LicenseValidityUnit, VALIDITY_UNITS } from "@/types";

const open = defineModel<boolean>("open", { required: true });

const unitOptions = computed(() =>
    VALIDITY_UNITS.filter((option) => option.value !== "lifetime"),
);

const form = useForm<{
    from_expires_at: string;
    amount: number;
    unit: Exclude<LicenseValidityUnit, "lifetime">;
}>({
    from_expires_at: "",
    amount: 1,
    unit: "months",
});

const submit = () => {
    form.post("/license-keys/bulk-extend", {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
            form.reset();
            form.amount = 1;
            form.unit = "months";
        },
    });
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle>Extend License Keys</DialogTitle>
                <DialogDescription>
                    Pick a cutoff date — every key in your team that expires on
                    or after this date will be extended by the duration below.
                    Lifetime keys are skipped.
                </DialogDescription>
            </DialogHeader>

            <div
                class="border-primary/30 bg-primary/5 text-foreground flex items-start gap-2 rounded-md border px-3 py-2 text-xs"
            >
                <CalendarClock class="size-4 shrink-0" aria-hidden="true" />
                <span>
                    Runs in the background. The dialog closes immediately and
                    you'll get a dashboard notification once all keys are
                    extended.
                </span>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="space-y-2">
                    <Label for="from_expires_at">
                        Extend keys expiring on or after
                    </Label>
                    <Input
                        id="from_expires_at"
                        type="datetime-local"
                        v-model="form.from_expires_at"
                        :aria-invalid="!!form.errors.from_expires_at"
                    />
                    <InputError :message="form.errors.from_expires_at" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-2">
                        <Label for="amount">Extend by</Label>
                        <Input
                            id="amount"
                            type="number"
                            min="1"
                            v-model="form.amount"
                            :aria-invalid="!!form.errors.amount"
                        />
                        <InputError :message="form.errors.amount" />
                    </div>

                    <div class="space-y-2">
                        <Label>Unit</Label>
                        <Select v-model="form.unit">
                            <SelectTrigger class="w-full">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="option in unitOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.unit" />
                    </div>
                </div>

                <DialogFooter>
                    <DialogClose as-child>
                        <Button variant="outline" type="button">
                            Cancel
                        </Button>
                    </DialogClose>
                    <Button type="submit" :disabled="form.processing">
                        <Loader2
                            v-if="form.processing"
                            class="size-4 animate-spin"
                        />
                        Extend
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
