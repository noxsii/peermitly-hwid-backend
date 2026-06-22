export interface HelpArticle {
    uuid: string;
    title: string;
    slug: string;
    excerpt: string | null;
    content: string;
    published_at: string | null;
}
