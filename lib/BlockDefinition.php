<?php

namespace JanHerman\PageBuilder;

use Kirby\Filesystem\F;

class BlockDefinition
{
    protected string $path;
    protected string $type;
    private array $cache = [];
    private ?array $templates = null;

    public function __construct(string $path)
    {
        $this->path = Utils::normalizePath($path);
        $this->type = basename($path);
    }

    private function hasCache(string $key): bool
    {
        return array_key_exists($key, $this->cache);
    }

    private function getCache(string $key): ?string
    {
        return $this->cache[$key] ?? null;
    }

    private function setCache(string $key, ?string $value): void
    {
        $this->cache[$key] = $value;
    }

    private function getFilePath(string $relative_path): ?string
    {
        if ($this->hasCache($relative_path)) {
            return $this->getCache($relative_path);
        }

        $path = null;

        if (
            !str_contains(ltrim($relative_path, '/'), '/') &&
            F::exists($prefixed_path = $this->path() . Utils::normalizePath($this->type() . '.' . $relative_path))
        ) { // block-name.file.extension
            $path = $prefixed_path;
        } elseif (F::exists($default_path = $this->path() . Utils::normalizePath($relative_path))) { // file.extension
            $path = $default_path;
        }

        $this->setCache($relative_path, $path);
        return $path;
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

    public function blueprint(): ?string
    {
        $blueprint_path = Utils::option('blockStructure.blueprint', 'blueprint.yml');
        return $this->getFilePath($blueprint_path);
    }

    public function controller(): ?string
    {
        $controller_path = Utils::option('blockStructure.controller', 'controller.php');
        return $this->getFilePath($controller_path);
    }

    public function templates(): array
    {
        if ($this->templates !== null) {
            return $this->templates;
        }

        $template_path = Utils::option('blockStructure.template', 'template.latte');
        $templates_directory_relative = Utils::option('blockStructure.templatesDirectory', 'templates');
        $templates_directory = $this->path() . Utils::normalizePath($templates_directory_relative);

        $templates = [];

        if ($default_template = $this->getFilePath($template_path)) {
            $templates['default'] = $default_template;
        }

        if (is_dir($templates_directory)) {
            $files = array_filter(glob($templates_directory . '/*'), 'is_file');
            foreach ($files as $path) {
                $templates[pathinfo($path, PATHINFO_FILENAME)] = Utils::normalizePath($path);
            }
        }

        $this->templates = $templates;
        return $this->templates;
    }

    public function template(string $name = 'default'): ?string
    {
        $templates = $this->templates();
        return $templates[$name] ?? null;
    }

    public function style(): ?string
    {
        $style_path = Utils::option('blockStructure.style', 'style.scss');
        return $this->getFilePath($style_path);
    }

    public function script(): ?string
    {
        $script_path = Utils::option('blockStructure.script', 'script.js');
        return $this->getFilePath($script_path);
    }

    public function viteEntry(?string $path = null): ?string
    {
        $path = $path ?? $this->script();

        if ($path === null) {
            return null;
        }

        $root = Utils::option('blocksDirectoryVite', 'blocks');
        $relative_path = substr($path, strlen($this->path()));

        $normalized_root = Utils::normalizePath($root);
        $normalized_path = Utils::normalizePath($this->type()) . Utils::normalizePath($relative_path);

        return ltrim($normalized_root . $normalized_path, '/');
    }

    public function viteEntryStyle(): ?string
    {
        return $this->viteEntry($this->style());
    }

    public function viteEntryScript(): ?string
    {
        return $this->viteEntry($this->script());
    }
}
