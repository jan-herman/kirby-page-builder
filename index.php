<?php

use Kirby\Cms\App as Kirby;
use Kirby\Data\Yaml;
use Kirby\Filesystem\F;
use JanHerman\PageBuilder\PageBuilder;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('jan-herman/page-builder', [
    'options' => [
        'blocksDirectory' => null,
        'blocks' => [],
        'blocksWysiwyg' => [],
    ],
    'fields' => [
        'pageBuilder' => 'JanHerman\PageBuilder\PageBuilderField'
    ],
    'blockModels' => page_builder()->blockModels(),
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
            }
        ]
    ),
    'templates' => [
        'nested-block' => __DIR__ . '/templates/nested-block.latte',
    ],
    'routes' => [
        [
            'pattern' => 'block-library',
            'action'  => function () {
                return false;
            }
        ],
    ],
    'pageMethods' => [
        'pageBuilderBlocks' => function () {
            return page_builder()->pageBlocks($this);
        },
        'pageBuilderBlockDefinitions' => function () {
            return page_builder()->pageBlockDefinitions($this);
        }
    ],
    'translations' => [
        'en' => Yaml::decode(F::read(__DIR__ . '/translations/en.yml')),
        'cs' => Yaml::decode(F::read(__DIR__ . '/translations/cs.yml')),
    ],
]);

function page_builder()
{
    return PageBuilder::getInstance();
}
