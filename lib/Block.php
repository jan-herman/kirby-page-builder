<?php

namespace JanHerman\PageBuilder;

use InvalidArgumentException;
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

    public function anchorId(string|array|null $fieldName = null, string $prefix = 'b-'): string
    {
        $key = $this->parent()->id() . ':' . $this->id;

        if (isset(self::$anchorIds[$key])) {
            return self::$anchorIds[$key];
        }

        if ($prefix !== '' && preg_match('/\A[a-z][a-z0-9-]*\z/i', $prefix) !== 1) {
            throw new InvalidArgumentException('Nonempty anchor prefixes must start with a letter and contain only letters, digits, and hyphens.');
        }

        $base = '';

        foreach ((array) $fieldName as $name) {
            if (!is_string($name)) {
                throw new InvalidArgumentException('Anchor field names must be strings.');
            }

            $field = $this->content()->get($name);

            if ($field->isNotEmpty()) {
                $base = (string) $field->slug()->value();
                break;
            }
        }

        if ($base === '') {
            $base = $prefix . substr(hash('sha256', $this->id), 0, 12);
        } elseif (ctype_digit($base[0])) {
            $base = $prefix . $base;
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
        $defaultData = [
            'block'   => $this,
            'content' => $this->content(),
        ];

        $controllerPath = $this->definition()->controller();

        if (!$controllerPath) {
            return $defaultData;
        }

        $data = array_merge($defaultData, $data);
        $controller = (array) Controller::load($controllerPath)->call(null, $data);

        return array_merge($defaultData, $controller);
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
