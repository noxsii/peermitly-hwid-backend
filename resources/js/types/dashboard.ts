export interface DashboardStats {
    active_license_keys: number;
    pending_license_keys: number;
    expiring_soon: number;
    customers: number;
    products: number;
    license_key_types: number;
    api_calls_last_24h: number;
}

export interface DashboardTeamMember {
    id: number;
    name: string;
    email: string;
    role: string;
    created_at: string | null;
}
