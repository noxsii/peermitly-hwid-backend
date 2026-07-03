export interface DashboardSubscription {
    plan: string;
    status: string;
    ends_at: string;
    days_remaining: number | null;
    is_lifetime: boolean;
    is_free: boolean;
    is_pro: boolean;
}
