export interface Team {
    id: number;
    uuid: string;
    name: string;
    owner_id: number;
    created_at: string | null;
    updated_at: string | null;
}

export interface OwnedTeam {
    id: number;
    uuid: string;
    name: string;
}
