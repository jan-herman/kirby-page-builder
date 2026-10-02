<?php

namespace JanHerman\PageBuilder;

use Kirby\Cms\Block as DefaultBlock;
use Kirby\Toolkit\Controller;
use Throwable;

class Block extends DefaultBlock
{
    protected static array $anchorIds = [];
    protected static array $usedAnchorIds = [];

    protected string $template = 'default';

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function definition(): BlockDefinition
    {
        return page_builder()->blockDefinition($this->type());
    }

    public function anchorId(string $fieldName = 'id'): string
    {
        $key = $this->parent()->id() . ':' . $this->id;

        if (isset(self::$anchorIds[$key])) {
            return self::$anchorIds[$key];
        }

        $field = $this->content()->get($fieldName);
        $base = $field->isNotEmpty() ? (string) $field->slug()->value() : '';

        if ($base === '') {
            $base = 'b-' . substr(hash('sha256', $this->id), 0, 12);
        } elseif (ctype_digit($base[0])) {
            $base = 'b-' . $base;
        }

        $anchorId = $base;
        $suffix = 2;

        while (isset(self::$usedAnchorIds[$anchorId])) {
            $anchorId = $base . '-' . $suffix++;
        }

        self::$usedAnchorIds[$anchorId] = true;

        return self::$anchorIds[$key] = $anchorId;
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

    public function toHtml(array $data = []): string
    {
        try {
            $kirby = $this->parent()->kirby();
            $data = array_merge($this->controller(), $data);
            $template = $this->getTemplate();
            $name = 'blocks/' . $this->type();

            if ($template !== 'default') {
                $name .= '/' . $template;
            }

            return (string) $kirby->snippet($name, $data, true);
        } catch (Throwable $e) {
			if ($kirby->option('debug') === true || $kirby->environment()->isLocal()) {
				throw $e;
			}

			error_log($e);

			return '';
		}
    }
}
