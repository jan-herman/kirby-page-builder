<?php

use Kirby\Cms\App as Kirby;
use Kirby\Data\Yaml;
use Kirby\Filesystem\F;
use JanHerman\PageBuilder\PageBuilder;
use JanHerman\PageBuilder\Models\NestedBlocks;

@include_once __DIR__ . '/vendor/autoload.php';

// Helper functions
if (!function_exists('page_builder')) {
    function page_builder(): PageBuilder
    {
        return PageBuilder::getInstance();
    }
}

Kirby::plugin('jan-herman/page-builder', [
    'options' => [
        'blocksDirectory' => kirby()->root('site') . '/blocks',
        'blocksDirectoryVite' => 'blocks',
        'blockLibrary' => [
            'slug' => 'block-library',
            'uuid' => 'block-library',
        ],
        'blockStructure' => [
            'templatesDirectory' => 'templates',
            'blueprint' => 'blueprint.yml',
            'controller' => 'controller.php',
            'template' => 'template.latte',
            'style' => 'style.scss',
            'script' => 'script.js',
        ],
        'blocks' => [],
        'blocksWysiwyg' => [],
    ],
    'fields' => [
        'pageBuilder' => 'JanHerman\PageBuilder\PageBuilderField'
    ],
    'blockModels' => page_builder()->blockModels(),
    'pageModels' => [
        'nested-blocks' => NestedBlocks::class,
    ],
    'snippets' => array_merge(
        page_builder()->blockTemplates(),
        [
            'page-builder'        => __DIR__ . '/snippets/page-builder.php',
            'blocks/nested-block' => __DIR__ . '/snippets/blocks/nested-block.php',
        ]
    ),
    'blueprints' => array_merge(
        page_builder()->blockBlueprints(),
        [
            // Pages
            'pages/nested-block'  => __DIR__ . '/blueprints/pages/nested-block.yml',
            'pages/nested-blocks' => __DIR__ . '/blueprints/pages/nested-blocks.yml',

            // Layouts
            'layouts/page-builder-block' => __DIR__ . '/blueprints/layouts/page-builder-block.yml',

            // Blocks
            'blocks/nested-block' => __DIR__ . '/blueprints/blocks/nested-block.yml',

            // Fields
            'fields/page-builder.default' => __DIR__ . '/blueprints/fields/page-builder.default.yml',
            'fields/page-builder'         => function () {
                return [
                    'extends'   => 'fields/page-builder.default',
                    'fieldsets' => option('jan-herman.page-builder.blocks', [])
                ];
            },
            'fields/page-builder-wysiwyg.default' => __DIR__ . '/blueprints/fields/page-builder-wysiwyg.default.yml',
            'fields/page-builder-wysiwyg'         => function () {
                return [
                    'extends'   => 'fields/page-builder-wysiwyg.default',
                    'fieldsets' => option('jan-herman.page-builder.blocksWysiwyg', [])
                ];
            },
            'fields/repeater' => __DIR__ . '/blueprints/fields/repeater.yml',
        ]
    ),
    'templates' => [
        'nested-block' => __DIR__ . '/templates/nested-block.latte',
    ],
    'areas' => require __DIR__ . '/config/areas.php',
    'routes' => require __DIR__ . '/config/routes.php',
    'commands' => require __DIR__ . '/config/commands.php',
    'pageMethods' => require __DIR__ . '/config/page-methods.php',
    'translations' => [
        'en' => Yaml::decode(F::read(__DIR__ . '/translations/en.yml')),
        'cs' => Yaml::decode(F::read(__DIR__ . '/translations/cs.yml')),
    ],
]);
