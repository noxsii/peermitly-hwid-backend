import type { Component } from "vue";

export interface LandingFeature {
    icon: Component;
    title: string;
    description: string;
}

export interface LandingTrustItem {
    icon: Component;
    label: string;
    href?: string;
}

export interface LandingStep {
    number: string;
    icon: Component;
    title: string;
    description: string;
}

export interface LandingUseCase {
    icon: Component;
    title: string;
    description: string;
}

export interface LandingFaqItem {
    question: string;
    answer: string;
}

export interface LandingSeoProps {
    siteName: string;
    canonical: string;
    ogImage: string;
}
