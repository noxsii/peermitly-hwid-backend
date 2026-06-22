export interface AppRelease {
    uuid: string;
    version: string;
    notes: string | null;
    file_name: string;
    file_size: number;
    created_at: string | null;
    download_url: string;
}
