export type LicenseKeyStatus =
    | "pending"
    | "active"
    | "expired"
    | "revoked"
    | "blocked";

export type LicenseKeyGeneratorType = "uuid" | "random" | "pattern";

export type LicenseValidityUnit =
    | "hours"
    | "days"
    | "weeks"
    | "months"
    | "years"
    | "lifetime";

export interface LicenseValidityUnitOption {
    value: LicenseValidityUnit;
    label: string;
}

export const VALIDITY_UNITS: readonly LicenseValidityUnitOption[] = [
    { value: "hours", label: "Hours" },
    { value: "days", label: "Days" },
    { value: "weeks", label: "Weeks" },
    { value: "months", label: "Months" },
    { value: "years", label: "Years" },
    { value: "lifetime", label: "Lifetime" },
] as const;

export interface LicenseKeyActivation {
    uuid: string;
    machine_id: string;
    hostname: string | null;
    ip_address: string | null;
    activated_at: string | null;
    last_seen_at: string | null;
}

export interface LicenseKey {
    uuid: string;
    key: string;
    status: LicenseKeyStatus;
    product: {
        uuid: string | null;
        name: string | null;
        slug: string | null;
    };
    customer: {
        uuid: string;
        email: string;
        name: string | null;
    } | null;
    type: {
        uuid: string | null;
        name: string | null;
    };
    validity_amount: number | null;
    validity_unit: LicenseValidityUnit;
    max_activations: number | null;
    requires_hwid_check: boolean;
    activated_at: string | null;
    expires_at: string | null;
    last_checked_at: string | null;
    check_count: number;
    revoked_at: string | null;
    revoked_reason: string | null;
    created_at: string | null;
    activations?: LicenseKeyActivation[];
}

export interface LicenseKeyType {
    uuid: string;
    name: string;
    description: string | null;
    generator_type: LicenseKeyGeneratorType;
    configuration: Record<string, unknown>;
    is_active: boolean;
    license_keys_count?: number;
    created_at: string | null;
    updated_at: string | null;
}

export interface ProductOption {
    uuid: string;
    name: string;
    slug: string;
    is_active: boolean;
    description?: string | null;
    license_keys_count?: number;
}

export interface CustomerOption {
    uuid: string;
    email: string;
    name: string | null;
    company: string | null;
    metadata?: Record<string, unknown> | null;
    license_keys_count?: number;
}

export interface LicenseKeySearchResult {
    uuid: string;
    key: string;
    status: LicenseKeyStatus;
    product: string | null;
    customer: string | null;
    expires_at: string | null;
}
