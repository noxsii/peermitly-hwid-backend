<script setup lang="ts">
import { Check, Copy } from "@lucide/vue";
import { useCopyToClipboard } from "@/composables/useCopyToClipboard";
import { useScrollReveal } from "@/composables/useScrollReveal";

const { target, isVisible } = useScrollReveal();
const { copied, copy } = useCopyToClipboard();

const endpoint = "POST /api/license-keys/check";

const snippet = `curl -X POST https://peermitly.de/api/license-keys/check \\
  -H "Authorization: Bearer <token>" \\
  -H "Content-Type: application/json" \\
  -d '{ "key": "PRMT-9F2A-4E11-XR07", "hwid": "a1b2c3d4" }'

# 200 OK
{
  "key": "PRMT-9F2A-4E11-XR07",
  "status": "active",
  "hwid": "a1b2c3d4",
  "expires_at": "2026-12-31T23:59:59Z"
}`;
</script>

<template>
    <section id="api" class="bg-background py-20 md:py-28">
        <div
            ref="target"
            :class="[
                'mx-auto grid max-w-6xl items-center gap-10 px-6 transition-all duration-700 ease-out md:grid-cols-2 md:gap-12',
                isVisible
                    ? 'translate-y-0 opacity-100'
                    : 'translate-y-4 opacity-0',
            ]"
        >
            <div class="max-w-lg">
                <p
                    class="text-muted-foreground text-xs font-medium tracking-[0.18em] uppercase"
                >
                    Integration
                </p>
                <h2
                    class="text-foreground mt-3 text-3xl font-semibold tracking-tight md:text-4xl"
                >
                    One endpoint. Token-secured.
                </h2>
                <p class="text-muted-foreground mt-6 text-base leading-7">
                    Validate and activate a license with a single call. The key
                    stays pending until this first request, so customers never
                    lose days they didn't use.
                </p>
            </div>

            <div
                class="bg-foreground text-background overflow-hidden rounded-2xl shadow-lg"
            >
                <div
                    class="border-background/15 flex items-center justify-between border-b px-4 py-3"
                >
                    <span class="font-mono text-xs tracking-wide opacity-80">
                        {{ endpoint }}
                    </span>
                    <button
                        type="button"
                        class="text-background/80 hover:text-background inline-flex items-center gap-1.5 text-xs transition-colors"
                        :aria-label="copied ? 'Copied' : 'Copy code'"
                        @click="copy(snippet)"
                    >
                        <component
                            :is="copied ? Check : Copy"
                            class="size-3.5"
                        />
                        {{ copied ? "Copied" : "Copy" }}
                    </button>
                </div>
                <pre
                    class="overflow-x-auto px-4 py-4 font-mono text-xs leading-6"
                ><code>{{ snippet }}</code></pre>
            </div>
        </div>
    </section>
</template>
