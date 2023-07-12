<?php

namespace JanHerman\PageBuilder;

use \F;

class PageBuilderBlockDefinition
{
    protected string $path;
    protected string $type;
    protected string $blueprint;
    protected string $controller;
    protected string $template;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->type = basename($path);
        $this->blueprint = F::exists($path . '/blueprint.yml') ? $path . '/blueprint.yml' : '';
        $this->controller = F::exists($path . '/controller.php') ? $path . '/controller.php' : '';
        $this->template = F::exists($path . '/template.latte') ? $path . '/template.latte' : '';
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
        return PageBuilderBlock::class;
    }

    public function blueprint(): string
    {
        return $this->blueprint;
    }

    public function controller(): string
    {
        return $this->controller;
    }

    public function template(): string
    {
        return $this->template;
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
