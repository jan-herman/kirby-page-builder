<?php

namespace JanHerman\PageBuilder;

use Kirby\Filesystem\F;

class BlockDefinition
{
    protected string $path;
    protected string $type;
    protected string $blueprint;
    protected string $controller;
    protected array $templates;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->type = basename($path);
        $this->blueprint = F::exists($path . '/blueprint.yml') ? $path . '/blueprint.yml' : '';
        $this->controller = F::exists($path . '/controller.php') ? $path . '/controller.php' : '';
        $this->templates = [];

        if (F::exists($path . '/template.latte')) {
            $this->templates[] = [
                'name' => '',
                'path' => $path . '/template.latte',
            ];
        }

        if (is_dir($path . '/templates')) {
            $templates = array_filter(glob($path . '/templates/*'), 'is_file');
            foreach ($templates as $path) {
                $this->templates[] = [
                    'name' => pathinfo($path, PATHINFO_FILENAME),
                    'path' => $path,
                ];
            }
        }
    }

    public function path(): string
    {
        return $this->path;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function model(): string
    {
        return Block::class;
    }

    public function blueprint(): string
    {
        return $this->blueprint;
    }

    public function controller(): string
    {
        return $this->controller;
    }

    public function templates(): array
    {
        return $this->templates;
    }

    public function style(): string
    {
        return F::exists($this->path . '/style.scss') ? $this->path . '/style.scss' : '';
    }

    public function script(): string
    {
        return F::exists($this->path . '/script.js') ? $this->path . '/script.js' : '';
    }

    public function viteEntry(): string
    {
        return $this->script() ? 'blocks/' . $this->type() . '/script.js' : '';
    }
}
