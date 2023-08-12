<?php

namespace JanHerman\PageBuilder;

use Kirby\Cms\Block;
use Kirby\Toolkit\Controller;

class PageBuilderBlock extends Block
{
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

        $data = array_merge($data, [
            'block' => $this
        ]);

        $controller = Controller::load($controller_path);
        $controller_data = (array) $controller->call(null, $data);

        return array_merge($parent_controller, $controller_data);
	}

    public function toHtml(): string
	{
        $kirby = $this->parent()->kirby();
        return (string) $kirby->snippet(
            'blocks/' . $this->type(),
            $this->controller(),
            true
        );
    }
}
