<?php

namespace JanHerman\PageBuilder;

use Kirby\Cms\Page as Page;

class PageBuilder
{
    protected static $instance;

    protected string $blocks_directory;
    protected array $blocks;

    private function __construct()
    {
        $this->setBlocksDirectory();
        $this->setBlocks();
    }

    public static function getInstance()
    {
        return self::$instance ??= new self();
    }

    public function setBlocksDirectory(string|null $blocks_directory = null): void
    {
        if ($blocks_directory !== null) {
            $this->blocks_directory = Utils::normalizePath($blocks_directory);
            return;
        }

        $this->blocks_directory = Utils::pathFromOption('blocksDirectory', kirby()->root('site') . '/blocks');
    }

    public function setBlocks(string|null $blocks_directory = null): void
    {
        $blocks_directory = $blocks_directory ?? $this->blocks_directory;
        $blocks = glob($blocks_directory . '/*', GLOB_ONLYDIR);
        $block_definitions = [];

        foreach ($blocks as $path) {
            $type = basename($path);
            $block_definitions[$type] = new BlockDefinition($path);
        }

        $this->blocks = $block_definitions;
    }

    public function blocksDirectory(): string
    {
        return $this->blocks_directory;
    }

    public function blockDefinitions(): array
    {
        return $this->blocks;
    }

    public function blockDefinition($block_type): ?BlockDefinition
    {
        return $this->blocks[$block_type] ?? null;
    }

    public function blockModels(): array
    {
        $models = [];

        foreach ($this->blocks as $block_type => $block) {
            $models[$block_type] = $block->model();
        }

        return $models;
    }

    public function blockBlueprints(): array
    {
        $blueprints = [];

        foreach ($this->blocks as $block_type => $block) {
            if ($block->blueprint()) {
                $blueprints['blocks/' . $block_type] = $block->blueprint();
            }
        }

        return $blueprints;
    }

    public function blockTemplates(): array
    {
        $templates = [];

        foreach ($this->blocks as $block_type => $block) {
            foreach ($block->templates() as $name => $path) {
                if ($name === 'default') {
                    $key = 'blocks/' . $block_type;
                } else{
                    $key = 'blocks/' . $block_type . '/' . $name;
                }

                $templates[$key] = $path;
            }
        }

        return $templates;
    }

    public function pageBlocks(Page $page): array
    {
        if (!$this->blocks) {
            return [];
        }

        $blocks = [];
        $page_fields = $page->blueprint()->fields();

        $page_builder_fields = array_filter($page_fields, function ($field) {
            return $field['type'] === 'pageBuilder';
        });

        foreach ($page_builder_fields as $field_name => $field_definition) {
            foreach ($page->content()->{$field_name}()->toBlocks() as $block) {
                $blocks[] = $block;

                if ($block->type() === 'nested-block') {
                    $nested_blocks_page = $block->nested_block()->toPage();

                    if (!$nested_blocks_page) {
                        continue;
                    }

                    $nested_blocks = $this->pageBlocks($nested_blocks_page);
                    array_push($blocks, ...$nested_blocks);
                }
            }
        }

        return $blocks;
    }

    public function pageBlockDefinitions(Page $page): array
    {
        $blocks = $this->pageBlocks($page);

        if (empty($blocks)) {
            return [];
        }

        $block_definitions = [];
        foreach ($blocks as $block) {
            $block_definitions[$block->type()] = $block->definition();
        }

        return $block_definitions;
    }
}
