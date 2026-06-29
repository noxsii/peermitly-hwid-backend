import matter from "gray-matter";
import MarkdownIt from "markdown-it";
import anchor from "markdown-it-anchor";
import Shiki from "@shikijs/markdown-it";

const DOCS_DIR = "/resources/js/docs/";

function slugify(text) {
    return String(text)
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, "")
        .replace(/\s+/g, "-")
        .replace(/-+/g, "-");
}

function stripHtml(html) {
    return html
        .replace(/<pre[\s\S]*?<\/pre>/g, " ")
        .replace(/<[^>]+>/g, " ")
        .replace(/&[a-z]+;/g, " ")
        .replace(/\s+/g, " ")
        .trim();
}

let mdPromise = null;

async function getMarkdown() {
    if (mdPromise) {
        return mdPromise;
    }

    mdPromise = (async () => {
        const md = new MarkdownIt({ html: true, linkify: true, typographer: true });

        md.use(
            await Shiki({
                defaultColor: false,
                themes: { light: "github-light", dark: "github-dark" },
            }),
        );

        md.use(anchor, { slugify, level: [2, 3] });

        return md;
    })();

    return mdPromise;
}

function collectHeadings(md, content) {
    const tokens = md.parse(content, {});
    const headings = [];

    for (let i = 0; i < tokens.length; i++) {
        const token = tokens[i];

        if (token.type !== "heading_open") {
            continue;
        }

        const depth = Number(token.tag.slice(1));

        if (depth !== 2 && depth !== 3) {
            continue;
        }

        const inline = tokens[i + 1];
        const text = inline && inline.type === "inline" ? inline.content : "";

        headings.push({ id: slugify(text), text, depth });
    }

    return headings;
}

export default function docsPlugin() {
    return {
        name: "vite-plugin-docs",
        enforce: "pre",
        async transform(code, id) {
            const normalized = id.split("?")[0].replace(/\\/g, "/");

            if (!normalized.endsWith(".md") || !normalized.includes(DOCS_DIR)) {
                return null;
            }

            const md = await getMarkdown();
            const { data, content } = matter(code);
            const html = md.render(content);
            const headings = collectHeadings(md, content);
            const text = stripHtml(html);

            const payload = {
                meta: data,
                html,
                headings,
                text,
            };

            return {
                code: `export default ${JSON.stringify(payload)};`,
                map: null,
            };
        },
    };
}
