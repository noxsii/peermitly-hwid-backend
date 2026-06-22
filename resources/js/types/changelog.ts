export interface Changelog {
    uuid: string;
    title: string;
    version: string | null;
    content: string;
    published_at: string | null;
}
