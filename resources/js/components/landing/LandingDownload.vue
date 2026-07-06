<script setup lang="ts">
import { Check, Copy, Download, TriangleAlert } from "@lucide/vue";
import Reveal from "@/components/landing/Reveal.vue";
import { Button } from "@/components/ui/button";
import { useCopyToClipboard } from "@/composables/useCopyToClipboard";

const DMG_URL = "https://peermitly.de/storage/releases/peermitly_universal.dmg";
const FIX_COMMAND = "xattr -cr /Applications/peermitly.app";

const { copied, copy } = useCopyToClipboard();
</script>

<template>
    <section
        id="download"
        class="bg-muted/30 border-border/60 scroll-mt-20 border-y py-24"
    >
        <div class="mx-auto max-w-2xl px-6 text-center">
            <Reveal>
                <span
                    class="text-primary inline-flex items-center gap-2 text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    <span class="bg-primary/60 size-1.5 rounded-full" />
                    Download
                </span>
                <h2
                    class="from-foreground to-primary/70 mt-3 bg-gradient-to-br bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                >
                    Get Peermitly for macOS
                </h2>
                <p class="text-muted-foreground mt-4 text-lg">
                    One universal app for Apple Silicon and Intel. Download,
                    drag to Applications, and you're running.
                </p>
            </Reveal>

            <Reveal :delay="80" class="mt-8">
                <a :href="DMG_URL" download class="inline-block">
                    <Button size="lg" class="group h-12 px-6 text-base">
                        <Download
                            class="transition-transform group-hover:-translate-y-0.5"
                        />
                        Download for macOS
                    </Button>
                </a>
                <p class="text-muted-foreground mt-3 text-xs">
                    Universal · Apple Silicon &amp; Intel · .dmg
                </p>
            </Reveal>

            <Reveal :delay="120" class="mt-10">
                <div
                    class="border-border/60 bg-card mx-auto max-w-xl rounded-2xl border p-5 text-left"
                >
                    <div class="flex items-start gap-3">
                        <span
                            class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg bg-amber-500/10 text-amber-600"
                            aria-hidden="true"
                        >
                            <TriangleAlert class="size-4" />
                        </span>
                        <div class="min-w-0">
                            <h3 class="text-sm font-semibold">
                                Open test phase — one quick step
                            </h3>
                            <p
                                class="text-muted-foreground mt-1 text-sm leading-6"
                            >
                                Peermitly is still in an open test phase and not
                                notarized yet, so macOS may refuse to open it.
                                After moving the app to
                                <strong class="text-foreground"
                                    >Applications</strong
                                >, run this once in Terminal, then launch it
                                normally:
                            </p>

                            <div
                                class="border-border/60 bg-muted/50 mt-3 flex items-center gap-2 rounded-lg border p-1.5 pl-3"
                            >
                                <code
                                    class="min-w-0 flex-1 overflow-x-auto font-mono text-xs whitespace-nowrap"
                                >
                                    {{ FIX_COMMAND }}
                                </code>
                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="sm"
                                    class="shrink-0"
                                    :aria-label="
                                        copied ? 'Copied' : 'Copy command'
                                    "
                                    @click="copy(FIX_COMMAND)"
                                >
                                    <Check
                                        v-if="copied"
                                        class="size-4 text-emerald-600"
                                    />
                                    <Copy v-else class="size-4" />
                                    {{ copied ? "Copied" : "Copy" }}
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </Reveal>
        </div>
    </section>
</template>
