<script setup lang="ts">
import { Form, Head, Link } from "@inertiajs/vue3";
import { Eye, EyeOff, Fingerprint, Loader2 } from "@lucide/vue";
import { ref } from "vue";
import { usePasskeyAuth } from "@/composables/usePasskeyAuth";
import LogoMark from "@/components/Logo.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";

defineOptions({ layout: "" });

const showPassword = ref(false);
const remember = ref(false);

const {
    passkeysSupported,
    processing: passkeyProcessing,
    error: passkeyError,
    loginWithPasskey,
} = usePasskeyAuth();
</script>

<template>
    <Head title="Sign in" />

    <main
        class="bg-muted/40 flex min-h-screen items-center justify-center px-4"
    >
        <div
            class="bg-card text-card-foreground w-full max-w-md rounded-2xl border p-8 shadow-sm"
        >
            <Link href="/" class="mb-6 inline-flex" aria-label="Peermitly home">
                <LogoMark size="size-11" />
            </Link>
            <div class="mb-6 space-y-1">
                <h1 class="text-2xl font-semibold tracking-tight">
                    Sign in 👋
                </h1>
                <p class="text-muted-foreground text-sm">
                    Enter your email and password to continue.
                </p>
            </div>

            <Form
                action="/login"
                method="post"
                class="space-y-5"
                #default="{ errors, processing }"
            >
                <div class="space-y-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        :aria-invalid="!!errors.email"
                    />
                    <p v-if="errors.email" class="text-destructive text-sm">
                        {{ errors.email }}
                    </p>
                </div>

                <div class="space-y-2">
                    <Label for="password">Password</Label>
                    <div class="relative">
                        <Input
                            id="password"
                            name="password"
                            :type="showPassword ? 'text' : 'password'"
                            autocomplete="current-password"
                            required
                            class="pr-10"
                            :aria-invalid="!!errors.password"
                        />
                        <button
                            type="button"
                            :aria-label="
                                showPassword ? 'Hide password' : 'Show password'
                            "
                            :aria-pressed="showPassword"
                            class="text-muted-foreground hover:text-foreground focus-visible:ring-ring/50 absolute inset-y-0 right-0 flex items-center justify-center rounded-md px-3 outline-none focus-visible:ring-2"
                            @click="showPassword = !showPassword"
                        >
                            <EyeOff v-if="showPassword" class="size-4" />
                            <Eye v-else class="size-4" />
                        </button>
                    </div>
                    <p v-if="errors.password" class="text-destructive text-sm">
                        {{ errors.password }}
                    </p>
                </div>

                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <Switch
                            id="remember"
                            v-model:checked="remember"
                            name="remember"
                            value="1"
                        />
                        <Label for="remember" class="cursor-pointer">
                            Remember me
                        </Label>
                    </div>

                    <Link
                        href="/forgot-password"
                        class="text-primary text-xs font-medium hover:underline"
                    >
                        Forgot password?
                    </Link>
                </div>

                <Button type="submit" class="w-full" :disabled="processing">
                    <Loader2 v-if="processing" class="size-4 animate-spin" />
                    Sign in
                </Button>
            </Form>

            <div v-if="passkeysSupported" class="mt-6 space-y-3">
                <div
                    class="text-muted-foreground flex items-center gap-3 text-xs"
                >
                    <span class="border-border flex-1 border-t" />
                    <span>or</span>
                    <span class="border-border flex-1 border-t" />
                </div>
                <Button
                    type="button"
                    variant="outline"
                    class="w-full"
                    :disabled="passkeyProcessing"
                    @click="loginWithPasskey"
                >
                    <Loader2
                        v-if="passkeyProcessing"
                        class="size-4 animate-spin"
                    />
                    <Fingerprint v-else class="size-4" />
                    Sign in with passkey
                </Button>
                <p
                    v-if="passkeyError"
                    class="text-destructive text-center text-xs"
                >
                    {{ passkeyError }}
                </p>
            </div>
        </div>
    </main>
</template>
