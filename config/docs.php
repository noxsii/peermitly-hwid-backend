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
            ],
        ],
        [
            'title' => 'Services',
            'items' => [
                ['slug' => 'nginx', 'title' => 'nginx'],
                ['slug' => 'dns', 'title' => 'DNS'],
            ],
        ],
        [
            'title' => 'Troubleshooting',
            'items' => [
                ['slug' => 'homebrew-php-path', 'title' => 'Shell is not using the Homebrew PHP'],
                ['slug' => 'homebrew-node-path', 'title' => 'Shell is not using the Homebrew Node'],
            ],
        ],
    ],
];
