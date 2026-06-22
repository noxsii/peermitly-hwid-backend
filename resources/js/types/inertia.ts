import type { AuthUser } from "./user";

export interface PageProps {
    auth: {
        user: AuthUser | null;
    };
    [key: string]: unknown;
}
