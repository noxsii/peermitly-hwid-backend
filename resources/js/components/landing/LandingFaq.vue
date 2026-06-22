<script setup lang="ts">
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/components/ui/accordion";
import { useScrollReveal } from "@/composables/useScrollReveal";
import type { LandingFaqItem } from "@/types/landing";

const { target, isVisible } = useScrollReveal();

const faqs: LandingFaqItem[] = [
    {
        question: "What is HWID lock?",
        answer: "An optional binding of a key to one machine fingerprint, so a single license can't be shared across machines.",
    },
    {
        question: "Why do keys stay pending until activation?",
        answer: "The runtime clock starts on the first activation, not on creation, so customers never lose unused days.",
    },
    {
        question: "Do I need to run any servers?",
        answer: "No. Peermitly is a fully hosted service — sign in, create keys and the activation API is ready. There is nothing to install or maintain.",
    },
    {
        question: "Is the activation API rate-limited?",
        answer: "Yes. The check endpoint is token-secured and throttled to protect against abuse.",
    },
    {
        question: "How do I revoke a key?",
        answer: "Revoke it from the dashboard. The next check for that key then fails immediately.",
    },
];
</script>

<template>
    <section id="faq" class="bg-background py-20 md:py-28">
        <div
            ref="target"
            :class="[
                'mx-auto max-w-3xl px-6 transition-all duration-700 ease-out',
                isVisible
                    ? 'translate-y-0 opacity-100'
                    : 'translate-y-4 opacity-0',
            ]"
        >
            <div class="max-w-2xl">
                <p
                    class="text-muted-foreground text-xs font-medium tracking-[0.18em] uppercase"
                >
                    FAQ
                </p>
                <h2
                    class="text-foreground mt-3 text-3xl font-semibold tracking-tight md:text-4xl"
                >
                    Questions, answered.
                </h2>
            </div>

            <Accordion type="single" collapsible class="mt-10 w-full">
                <AccordionItem
                    v-for="faq in faqs"
                    :key="faq.question"
                    :value="faq.question"
                >
                    <AccordionTrigger class="text-left">
                        {{ faq.question }}
                    </AccordionTrigger>
                    <AccordionContent class="text-muted-foreground">
                        {{ faq.answer }}
                    </AccordionContent>
                </AccordionItem>
            </Accordion>
        </div>
    </section>
</template>
