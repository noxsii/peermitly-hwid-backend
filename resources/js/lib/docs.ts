import type { DocPage, DocSection } from "@/types";

type DocPayload = Omit<DocPage, "slug">;

const modules = import.meta.glob<{ default: DocPayload }>("../docs/*.md", {
    eager: true,
});

const pages: Record<string, DocPage> = {};

const EMPTY: DocPayload = { meta: {}, html: "", headings: [], text: "" };

for (const path in modules) {
    const slug = path.split("/").pop()!.replace(/\.md$/, "");

    const payload = modules[path].default;
    const valid =
        payload != null &&
        typeof payload === "object" &&
        typeof (payload as DocPayload).html === "string";

    pages[slug] = { slug, ...(valid ? payload : EMPTY) };
}

export function getDoc(slug: string): DocPage | undefined {
    return pages[slug];
}

export function docTitle(slug: string, fallback: string): string {
    return pages[slug]?.meta.title ?? fallback;
}

export interface SearchHit {
    slug: string;
    title: string;
    section: string;
    snippet: string;
}

function buildSnippet(text: string, query: string): string {
    const index = text.toLowerCase().indexOf(query.toLowerCase());

    if (index === -1) {
        return text.slice(0, 100).trim();
    }

    const start = Math.max(0, index - 40);
    const end = Math.min(text.length, index + query.length + 60);

    return `${start > 0 ? "…" : ""}${text.slice(start, end).trim()}${end < text.length ? "…" : ""}`;
}

export function searchDocs(
    query: string,
    sections: DocSection[],
    limit = 8,
): SearchHit[] {
    const term = query.trim().toLowerCase();

    if (term.length < 2) {
        return [];
    }

    const hits: SearchHit[] = [];

    for (const section of sections) {
        for (const item of section.items) {
            const page = pages[item.slug];

            if (!page) {
                continue;
            }

            const haystack = `${item.title} ${page.text}`.toLowerCase();

            if (!haystack.includes(term)) {
                continue;
            }

            hits.push({
                slug: item.slug,
                title: item.title,
                section: section.title,
                snippet: buildSnippet(page.text, term),
            });

            if (hits.length >= limit) {
                return hits;
            }
        }
    }

    return hits;
}
