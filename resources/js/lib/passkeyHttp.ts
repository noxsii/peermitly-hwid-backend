export async function postJson<T = unknown>(
    url: string,
    body: Record<string, unknown> = {},
): Promise<T> {
    const token = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    const response = await fetch(url, {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            ...(token ? { "X-CSRF-TOKEN": token } : {}),
        },
        body: JSON.stringify(body),
    });

    if (!response.ok) {
        const text = await response.text();
        throw new Error(`Passkey request failed: ${response.status} ${text}`);
    }

    return (await response.json()) as T;
}
