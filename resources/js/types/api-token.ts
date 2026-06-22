export interface ApiTokenAbility {
    value: string;
    label: string;
}

export interface ApiToken {
    id: number;
    name: string;
    abilities: string[];
    last_used_at: string | null;
    created_at: string | null;
}

export interface IssuedApiToken extends ApiToken {
    plain_text_token: string;
}
