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
        q: "Is the spoofer really undetectable?",
        a: "Yes. Peermitly rewrites every hardware identifier and clears the traces anti-cheats use to link your old machine. We update continuously to stay ahead of detection.",
    },
    {
        q: "Do I need to spoof after every ban?",
        a: "Spoof once before you play. If you ever catch a new HWID ban, just run the spoofer again — you get unlimited rotations on every plan.",
    },
    {
        q: "Which games are supported?",
        a: "20+ titles including Valorant, Call of Duty, Fortnite, Apex Legends, Rust, PUBG, CS2 and more. New games are added every month.",
    },
    {
        q: "Is it safe for my PC?",
        a: "Every change is backed up automatically and fully reversible. You can restore your original identifiers at any time with one click.",
    },
    {
        q: "How do I get the tool after paying?",
        a: "Instantly. Right after checkout the download and your license appear in your dashboard — no waiting, no manual activation.",
    },
    {
        q: "What are the system requirements?",
        a: "Windows 11 (64-bit) with administrator rights. That's it.",
    },
];
</script>

<template>
    <section id="faq" class="scroll-mt-20 py-24">
        <div class="mx-auto max-w-3xl px-6">
            <Reveal class="text-center">
                <span
                    class="text-primary text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    FAQ
                </span>
                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
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
