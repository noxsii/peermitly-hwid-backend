<?php

declare(strict_types=1);

return [
    /*
     * Documentation navigation. This is the source of truth for valid slugs
     * and the sidebar order. Each slug maps to a markdown file at
     * resources/js/docs/{slug}.md.
     */
    'sections' => [
        [
            'title' => 'Setup',
            'items' => [
                ['slug' => 'setup', 'title' => 'Setup'],
            ],
        ],
        [
            'title' => 'Getting Started',
            'items' => [
                ['slug' => 'introduction', 'title' => 'Introduction'],
                ['slug' => 'starter', 'title' => 'Starter guide'],
            ],
        ],
        [
            'title' => 'Binaries',
            'items' => [
                ['slug' => 'php', 'title' => 'PHP versions & settings'],
                ['slug' => 'node', 'title' => 'Node versions'],
                ['slug' => 'python', 'title' => 'Python versions'],
            ],
        ],
        [
            'title' => 'Services',
            'items' => [
                ['slug' => 'nginx', 'title' => 'nginx'],
                ['slug' => 'dns', 'title' => 'DNS'],
                ['slug' => 'mail', 'title' => 'Mail', 'pro' => true],
            ],
        ],
        [
            'title' => 'Sites',
            'items' => [
                ['slug' => 'blank-php', 'title' => 'Blank PHP'],
                ['slug' => 'laravel', 'title' => 'Laravel'],
                ['slug' => 'symfony', 'title' => 'Symfony'],
                ['slug' => 'vue', 'title' => 'Vue'],
                ['slug' => 'react', 'title' => 'React'],
                ['slug' => 'nuxt', 'title' => 'Nuxt', 'pro' => true],
                ['slug' => 'astro', 'title' => 'Astro', 'pro' => true],
                ['slug' => 'nextjs', 'title' => 'Next.js', 'pro' => true],
            ],
        ],
        [
            'title' => 'Databases',
            'items' => [
                ['slug' => 'mariadb', 'title' => 'MariaDB'],
                ['slug' => 'mysql', 'title' => 'MySQL'],
                ['slug' => 'postgresql', 'title' => 'PostgreSQL'],
                ['slug' => 'mongodb', 'title' => 'MongoDB', 'pro' => true],
            ],
        ],
        [
            'title' => 'Search',
            'items' => [
                ['slug' => 'meilisearch', 'title' => 'Meilisearch'],
                ['slug' => 'typesense', 'title' => 'Typesense'],
            ],
        ],
        [
            'title' => 'Tools',
            'items' => [
                ['slug' => 'ide', 'title' => 'IDE integration'],
                ['slug' => 'debug', 'title' => 'Debug', 'pro' => true],
                ['slug' => 'profiler', 'title' => 'Profiler', 'pro' => true],
                ['slug' => 'composer', 'title' => 'Composer graph', 'pro' => true],
                ['slug' => 'sidebar-editor', 'title' => 'Sidebar Editor', 'pro' => true],
            ],
        ],
        [
            'title' => 'Troubleshooting',
            'items' => [
                ['slug' => 'homebrew-php-path', 'title' => 'Shell is not using the Homebrew PHP'],
                ['slug' => 'homebrew-node-path', 'title' => 'Shell is not using the Homebrew Node'],
                ['slug' => 'homebrew-python-path', 'title' => 'Shell is not using the Homebrew Python'],
                ['slug' => 'homebrew-database-path', 'title' => 'Shell is not using the managed database'],
            ],
        ],
    ],
];
