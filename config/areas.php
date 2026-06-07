<?php

use Kirby\Cms\App as Kirby;

return [
    'block-library' => function (Kirby $kirby) {
        $slug = option('jan-herman.page-builder.blockLibrary.slug');
        $page = $kirby->page($slug);

        if (!$page?->exists()) {
            return [];
        }

        $panel_path = $page->panel()->path();

        return[
            'label'   => t('jan-herman.page-builder.page.nested-blocks.menuTitle'),
            'icon'    => 'stacked-view',
            'menu'    => true,
            'link'    => $panel_path,
            'current' => str_contains($kirby->request()->path()->toString(), $panel_path),
        ];
    },
];
