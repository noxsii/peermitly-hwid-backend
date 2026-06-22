import type { VariantProps } from "class-variance-authority";
import { cva } from "class-variance-authority";

export { default as Badge } from "./Badge.vue";

export const badgeVariants = cva(
    "inline-flex items-center gap-1 rounded-md border px-2 py-0.5 text-xs font-medium tracking-wide whitespace-nowrap select-none",
    {
        variants: {
            variant: {
                default: "bg-muted text-foreground border-transparent",
                success:
                    "bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-transparent",
                warning:
                    "bg-amber-500/10 text-amber-600 dark:text-amber-400 border-transparent",
                destructive:
                    "bg-destructive/10 text-destructive border-transparent",
                info: "bg-sky-500/10 text-sky-600 dark:text-sky-400 border-transparent",
                outline: "bg-transparent text-foreground border-border",
            },
        },
        defaultVariants: { variant: "default" },
    },
);
export type BadgeVariants = VariantProps<typeof badgeVariants>;
