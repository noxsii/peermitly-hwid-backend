export interface DocHeading {
    id: string;
    text: string;
    depth: number;
}

export interface DocMeta {
    title?: string;
    description?: string;
    [key: string]: unknown;
}

export interface DocPage {
    slug: string;
    meta: DocMeta;
    html: string;
    headings: DocHeading[];
    text: string;
}

export interface DocNavItem {
    slug: string;
    title: string;
}

export interface DocSection {
    title: string;
    items: DocNavItem[];
}
