export type UserRole = "user" | "admin" | "super_admin";

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    role: UserRole;
    created_at: string;
    updated_at: string;
}

export type AuthUser = User;
