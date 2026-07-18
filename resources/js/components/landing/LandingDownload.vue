<script setup lang="ts">
import { Check, Copy, Download, TriangleAlert } from "@lucide/vue";
import Reveal from "@/components/landing/Reveal.vue";
import { Button } from "@/components/ui/button";
import { useCopyToClipboard } from "@/composables/useCopyToClipboard";

const DMG_URL = "https://peermitly.de/storage/releases/peermitly_universal.dmg";
const FIX_COMMAND = "xattr -cr /Applications/peermitly.app";

withDefaults(
    defineProps<{
        dashboard?: boolean;
    }>(),
    {
        dashboard: false,
    },
);

const { copied, copy } = useCopyToClipboard();
</script>

<template>
    <section
        id="download"
        :data-dashboard-download="dashboard ? '' : undefined"
        :class="[
            'bg-muted/30 border-border scroll-mt-20 border-y',
            dashboard ? 'py-10 sm:py-14' : 'py-20 sm:py-24',
        ]"
    >
        <div
            :class="[
                'mx-auto max-w-2xl text-center',
                dashboard ? 'px-4 sm:px-6' : 'px-6',
            ]"
        >
            <Reveal>
                <span
                    class="text-primary inline-flex items-center gap-2 text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    Download
                </span>
                <h2
                    :class="[
                        'mt-4 font-medium tracking-[-0.045em]',
                        dashboard
                            ? 'text-2xl sm:text-4xl'
                            : 'text-3xl sm:text-5xl',
                    ]"
                >
                    Get Peermitly for macOS
                </h2>
                <p
                    :class="[
                        'text-muted-foreground mt-4',
                        dashboard ? 'text-base sm:text-lg' : 'text-lg',
                    ]"
                >
                    One universal app for Apple Silicon and Intel. Download,
                    drag to Applications, and you're running.
                </p>
            </Reveal>

            <Reveal :delay="80" class="mt-8">
                <a
                    :href="DMG_URL"
                    download
                    :class="
                        dashboard ? 'block sm:inline-block' : 'inline-block'
                    "
                >
                    <Button
                        size="lg"
                        :class="[
                            'group h-12 rounded-md px-6 text-base',
                            dashboard ? 'w-full sm:w-auto' : '',
                        ]"
                    >
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
                    :class="[
                        'border-border bg-card mx-auto max-w-xl border text-left',
                        dashboard ? 'p-4 sm:p-5' : 'p-5',
                    ]"
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
                                :class="[
                                    'border-border/60 bg-muted/50 mt-3 flex rounded-lg border',
                                    dashboard
                                        ? 'min-w-0 flex-col gap-1.5 p-2 sm:flex-row sm:items-center sm:gap-2 sm:p-1.5 sm:pl-3'
                                        : 'items-center gap-2 p-1.5 pl-3',
                                ]"
                            >
                                <code
                                    :class="[
                                        'min-w-0 flex-1 overflow-x-auto font-mono text-xs whitespace-nowrap',
                                        dashboard
                                            ? 'w-full py-1 sm:w-auto sm:py-0'
                                            : '',
                                    ]"
                                >
                                    {{ FIX_COMMAND }}
                                </code>
                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="sm"
                                    :class="[
                                        'shrink-0',
                                        dashboard ? 'w-full sm:w-auto' : '',
                                    ]"
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
