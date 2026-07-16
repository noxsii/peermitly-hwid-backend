export interface News {
    uuid: string;
    slug: string;
    title: string;
    description: string;
    image_url: string | null;
    content: string;
    published_at: string | null;
}
