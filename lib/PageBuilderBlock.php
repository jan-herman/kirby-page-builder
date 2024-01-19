<?php

namespace JanHerman\PageBuilder;

use Kirby\Cms\Block;
use Kirby\Toolkit\Controller;

class PageBuilderBlock extends Block
{
    protected string $template = '';

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function definition(): PageBuilderBlockDefinition
    {
        return page_builder()->blockDefinition($this->type());
    }

    public function controller(array $data = []): array
    {
        $controller_path = $this->definition()->controller();
        $parent_controller = parent::controller();

        if (!$controller_path) {
            return $parent_controller;
        }

        $data = array_merge($parent_controller, $data);
        $controller = (array) Controller::load($controller_path)->call(null, $data);

        return array_merge($parent_controller, $controller);
    }

    public function toHtml(): string
    {
        $kirby = $this->parent()->kirby();
        $data = $this->controller();
        $name = 'blocks/' . $this->type();

        if ($this->template) {
            $name .= '/' . $this->template;
        }

        return (string) $kirby->snippet($name, $data, true);
    }
}
