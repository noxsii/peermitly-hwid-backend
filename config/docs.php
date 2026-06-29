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
            'title' => 'Getting Started',
            'items' => [
                ['slug' => 'introduction', 'title' => 'Introduction'],
            ],
        ],
        [
            'title' => 'Troubleshooting',
            'items' => [
                ['slug' => 'homebrew-php-path', 'title' => 'Shell is not using the Homebrew PHP'],
            ],
        ],
    ],
];
