<?php

use Kirby\CLI\CLI;

return [
    'make:block-library' => [
        'description' => 'Create new block library page',
        'args' => [],
        'command' => function (CLI $cli): void {
            $kirby = kirby();
            $slug = option('jan-herman.page-builder.blockLibrary.slug');
            $uuid = option('jan-herman.page-builder.blockLibrary.uuid');

            if ($kirby->page('page://' . $uuid)?->exists()) {
                $cli->error('Page with UUID "' . $uuid . '" already exists.');
                return;
            }

            $kirby->impersonate(
                'kirby',
                fn () => $kirby->site()->createChild([
                    'slug' => $slug,
                    'template' => 'nested-blocks',
                    'content' => [
                        'uuid' => $uuid,
                    ]
                ])->changeStatus('unlisted')
            );

            $cli->success('Block library page created.');
        }
    ],
];
