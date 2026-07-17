<script lang="ts" setup>
withDefaults(
    defineProps<{
        size?: string;
    }>(),
    {
        size: "w-12 h-12",
    },
);
</script>

<template>
    <svg
        :class="size"
        class="logo"
        viewBox="0 0 64 64"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        role="img"
        aria-label="peermitly logo"
    >
        <defs>
            <linearGradient
                id="logo-chip"
                x1="12"
                y1="10"
                x2="52"
                y2="54"
                gradientUnits="userSpaceOnUse"
            >
                <stop offset="0" stop-color="#86efac" />
                <stop offset="0.5" stop-color="#22c55e" />
                <stop offset="1" stop-color="#15803d" />
            </linearGradient>
            <linearGradient
                id="logo-ghost"
                x1="22"
                y1="20"
                x2="42"
                y2="46"
                gradientUnits="userSpaceOnUse"
            >
                <stop offset="0" stop-color="#f0fdf4" />
                <stop offset="1" stop-color="#bbf7d0" />
            </linearGradient>
            <radialGradient
                id="logo-glow"
                cx="32"
                cy="32"
                r="26"
                gradientUnits="userSpaceOnUse"
            >
                <stop offset="0" stop-color="#22c55e" stop-opacity="0.45" />
                <stop offset="1" stop-color="#22c55e" stop-opacity="0" />
            </radialGradient>
        </defs>

        <!-- ambient glow -->
        <circle cx="32" cy="32" r="26" fill="url(#logo-glow)" />

        <!-- CPU pins -->
        <g fill="url(#logo-chip)">
            <rect x="22" y="6" width="3.5" height="7" rx="1.2" />
            <rect x="30.25" y="6" width="3.5" height="7" rx="1.2" />
            <rect x="38.5" y="6" width="3.5" height="7" rx="1.2" />
            <rect x="22" y="51" width="3.5" height="7" rx="1.2" />
            <rect x="30.25" y="51" width="3.5" height="7" rx="1.2" />
            <rect x="38.5" y="51" width="3.5" height="7" rx="1.2" />
            <rect x="6" y="22" width="7" height="3.5" rx="1.2" />
            <rect x="6" y="30.25" width="7" height="3.5" rx="1.2" />
            <rect x="6" y="38.5" width="7" height="3.5" rx="1.2" />
            <rect x="51" y="22" width="7" height="3.5" rx="1.2" />
            <rect x="51" y="30.25" width="7" height="3.5" rx="1.2" />
            <rect x="51" y="38.5" width="7" height="3.5" rx="1.2" />
        </g>

        <!-- chip body -->
        <rect x="11" y="11" width="42" height="42" rx="9" fill="#1c1917" />
        <rect
            x="11"
            y="11"
            width="42"
            height="42"
            rx="9"
            fill="none"
            stroke="url(#logo-chip)"
            stroke-width="2.5"
        />

        <!-- circuit nodes inside the die -->
        <g fill="#22c55e" opacity="0.55">
            <circle cx="17.5" cy="17.5" r="1.4" />
            <circle cx="46.5" cy="17.5" r="1.4" />
            <circle cx="17.5" cy="46.5" r="1.4" />
            <circle cx="46.5" cy="46.5" r="1.4" />
        </g>

        <!-- glitch echo of the ghost (the "spoof") -->
        <g class="logo-glitch" opacity="0.55">
            <path
                d="M23 41V29c0-5 4-9 9-9s9 4 9 9v12l-3.6-3.4L33.8 41 32 38.6 30.2 41l-3.6-3.4z"
                fill="#22d3ee"
                transform="translate(-1.4 0)"
            />
            <path
                d="M23 41V29c0-5 4-9 9-9s9 4 9 9v12l-3.6-3.4L33.8 41 32 38.6 30.2 41l-3.6-3.4z"
                fill="#fb7185"
                transform="translate(1.4 0)"
            />
        </g>

        <!-- ghost = hidden / spoofed identity -->
        <path
            class="logo-ghost"
            d="M23 41V29c0-5 4-9 9-9s9 4 9 9v12l-3.6-3.4L33.8 41 32 38.6 30.2 41l-3.6-3.4z"
            fill="url(#logo-ghost)"
        />
        <g fill="#1c1917">
            <circle cx="28.5" cy="29.5" r="1.9" />
            <circle cx="35.5" cy="29.5" r="1.9" />
        </g>
    </svg>
</template>

<style scoped>
.logo {
    filter: drop-shadow(0 2px 8px rgba(34, 197, 94, 0.35));
}

.logo-glitch {
    mix-blend-mode: screen;
    animation: logo-glitch 3.2s steps(1) infinite;
}

.logo-ghost {
    animation: logo-float 4s ease-in-out infinite;
    transform-box: fill-box;
    transform-origin: center;
}

@keyframes logo-glitch {
    0%,
    88%,
    100% {
        opacity: 0;
        transform: translateX(0);
    }
    90% {
        opacity: 0.6;
        transform: translateX(-1px);
    }
    94% {
        opacity: 0.6;
        transform: translateX(1px);
    }
    96% {
        opacity: 0.6;
        transform: translateX(-0.5px);
    }
}

@keyframes logo-float {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-1px);
    }
}

@media (prefers-reduced-motion: reduce) {
    .logo-glitch,
    .logo-ghost {
        animation: none;
    }
}
</style>
