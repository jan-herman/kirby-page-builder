<?php

namespace JanHerman\PageBuilder;

use Kirby\Form\Field\BlocksField;

/**
 * https://github.com/getkirby/kirby/issues/3961
 */
class PageBuilderField extends BlocksField
{
    protected string|null $css_class;

    public function __construct(array $params = [])
    {
        $this->setCssClass($params['cssClass'] ?? null);

        parent::__construct($params);
    }

    public function extends()
    {
        return 'blocks';
    }

    protected function setCssClass(string|null $css_class)
    {
        $this->css_class = $css_class;
    }

    public function cssClass()
    {
        return $this->css_class;
    }

    public function props(): array
    {
        return [
            'cssClass' => $this->cssClass()
        ] + parent::props();
    }
}
