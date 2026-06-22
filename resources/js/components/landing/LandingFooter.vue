<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

const year = new Date().getFullYear();
</script>

<template>
    <footer class="border-border/60 border-t">
        <div class="mx-auto max-w-6xl px-6 py-14">
            <div class="flex flex-col gap-10 md:flex-row md:justify-between">
                <div class="max-w-xs">
                    <span
                        class="text-foreground inline-flex items-center gap-2 text-base font-semibold tracking-tight"
                    >
                        <span
                            class="bg-primary text-primary-foreground inline-flex size-7 items-center justify-center rounded-lg text-xs font-bold"
                            aria-hidden="true"
                            >P</span
                        >
                        Peermitly
                    </span>
                    <p class="text-muted-foreground mt-4 text-sm leading-6">
                        License keys, HWID binding and an activation API for
                        your own products — in one calm dashboard.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-10 text-sm sm:grid-cols-3">
                    <div>
                        <p class="text-foreground font-medium">Product</p>
                        <ul class="text-muted-foreground mt-3 space-y-2">
                            <li>
                                <a
                                    href="#how"
                                    class="hover:text-foreground transition-colors"
                                    >How it works</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#api"
                                    class="hover:text-foreground transition-colors"
                                    >API</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#faq"
                                    class="hover:text-foreground transition-colors"
                                    >FAQ</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p class="text-foreground font-medium">Resources</p>
                        <ul class="text-muted-foreground mt-3 space-y-2">
                            <li>
                                <a
                                    href="https://peermitly.de/docs/api"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="hover:text-foreground transition-colors"
                                    >Documentation</a
                                >
                            </li>
                            <li>
                                <a
                                    href="https://peermitly.betteruptime.com/"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="hover:text-foreground transition-colors"
                                    >Status</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p class="text-foreground font-medium">Legal</p>
                        <ul class="text-muted-foreground mt-3 space-y-2">
                            <li>
                                <Link
                                    :href="route('privacy')"
                                    class="hover:text-foreground transition-colors"
                                    >Privacy</Link
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div
                class="border-border/60 text-muted-foreground mt-12 border-t pt-6 text-sm"
            >
                © {{ year }} Peermitly
            </div>
        </div>
    </footer>
</template>
