<script setup lang="ts">
import Reveal from "@/components/landing/Reveal.vue";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/components/ui/accordion";

const faqs = [
    {
        q: "Which platforms does Peermitly run on?",
        a: "macOS — both Apple Silicon and Intel Macs. A native app built specifically for the Mac.",
    },
    {
        q: "Do I need Docker or a virtual machine?",
        a: "No. Peermitly runs services natively for much faster cold starts and lower memory use — no containers, no VM, no Docker Desktop required.",
    },
    {
        q: "Which frameworks are supported?",
        a: "20+ stacks including Laravel, Symfony, WordPress, Statamic, Node.js, Next.js, Django, Rails and more. Peermitly auto-detects the stack in your project folder.",
    },
    {
        q: "Can I run multiple PHP or Node versions?",
        a: "Yes. Switch the runtime version per project with one click — different sites can run on different versions side by side without conflicts.",
    },
    {
        q: "Is local HTTPS included?",
        a: "Yes. Every site gets a secure .peer domain with a trusted local certificate automatically — no manual cert setup, no browser warnings.",
    },
    {
        q: "How do I get started after signing up?",
        a: "Instantly. Right after checkout the download and your license appear in your dashboard — no waiting, no manual activation.",
    },
];
</script>

<template>
    <section id="faq" class="scroll-mt-20 py-24">
        <div class="mx-auto max-w-3xl px-6">
            <Reveal class="text-center">
                <span
                    class="text-primary inline-flex items-center gap-2 text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    <span class="bg-primary/60 size-1.5 rounded-full" />
                    FAQ
                </span>
                <h2
                    class="from-foreground to-primary/70 mt-3 bg-gradient-to-br bg-clip-text text-3xl font-bold tracking-tight text-transparent sm:text-4xl"
                >
                    Questions, answered
                </h2>
            </Reveal>

            <Reveal class="mt-10">
                <Accordion type="single" collapsible class="w-full">
                    <AccordionItem
                        v-for="(faq, i) in faqs"
                        :key="faq.q"
                        :value="`item-${i}`"
                        class="border-border/60"
                    >
                        <AccordionTrigger
                            class="text-left text-base font-medium"
                        >
                            {{ faq.q }}
                        </AccordionTrigger>
                        <AccordionContent
                            class="text-muted-foreground text-sm leading-6"
                        >
                            {{ faq.a }}
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>
            </Reveal>
        </div>
    </section>
</template>
