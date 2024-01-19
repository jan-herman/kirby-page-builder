<?php

use Kirby\Cms\App as Kirby;
use JanHerman\PageBuilder\PageBuilder;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('jan-herman/page-builder', [
    'options' => [
        'blocksDirectory' => null,
        'blocks' => []
    ],
    'fields' => [
        'pageBuilder' => 'JanHerman\PageBuilder\PageBuilderField'
    ],
    'blockModels' => page_builder()->blockModels(),
    'snippets' => page_builder()->blockTemplates(),
    'blueprints' => array_merge(
        page_builder()->blockBlueprints(),
        [
            'layouts/page-builder-block' => __DIR__ . '/blueprints/layouts/page-builder-block.yml',
            'fields/page-builder' => function () {
                return [
                    'label' => t('jan-herman.page-builder.field.label'),
                    'type' => 'pageBuilder',
                    'cssClass' => 'jh-page-builder-field',
                    'pretty' => true,
                    'fieldsets' => option('jan-herman.page-builder.blocks')
                ];
            },
            'fields/page-builder-wysiwyg' => function () {
                return [
                    'label' => t('jan-herman.page-builder.wysiwyg-field.label'),
                    'type' => 'pageBuilder',
                    'cssClass' => 'jh-page-builder-field-wysiwyg',
                    'pretty' => true,
                    'empty' => t('jan-herman.page-builder.wysiwyg-field.empty'),
                    'fieldsets' => option('jan-herman.page-builder.blocksWysiwyg')
                ];
            }
        ]
    ),
    'pageMethods' => [
        'pageBuilderBlocks' => function () {
            return page_builder()->pageBlocks($this);
        },
        'pageBuilderBlockDefinitions' => function () {
            return page_builder()->pageBlockDefinitions($this);
        }
    ],
    'translations' => [
        'en' => [
            'jan-herman.page-builder.field.label'         => 'Page Builder',
            'jan-herman.page-builder.wysiwyg-field.label' => 'Content',
            'jan-herman.page-builder.wysiwyg-field.empty' => 'Add some content',
        ],
        'cs' => [
            'jan-herman.page-builder.field.label'         => 'Page Builder',
            'jan-herman.page-builder.wysiwyg-field.label' => 'Obsah',
            'jan-herman.page-builder.wysiwyg-field.empty' => 'Přidejte obsah',
        ]
    ]
]);

function page_builder()
{
    return PageBuilder::getInstance();
}
