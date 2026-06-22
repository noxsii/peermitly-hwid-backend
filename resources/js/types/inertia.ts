import type { DashboardSubscription } from "./dashboard";
import type { AuthUser } from "./user";

export interface PageProps {
    auth: {
        user: AuthUser | null;
        subscription: DashboardSubscription | null;
    };
    [key: string]: unknown;
}
