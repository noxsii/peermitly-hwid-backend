import type { FunctionalComponent, SVGAttributes } from "vue";

export type LucideIcon = FunctionalComponent<SVGAttributes>;

export interface NavItem {
    label: string;
    href: string;
    icon: LucideIcon;
}
