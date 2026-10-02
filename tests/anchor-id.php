<?php

// Run with: php tests/anchor-id.php

use JanHerman\PageBuilder\Block;
use Kirby\Cms\App;
use Kirby\Cms\Page;

require dirname(__DIR__) . '/vendor/autoload.php';

new App(['roots' => ['index' => sys_get_temp_dir() . '/kirby-anchor-id-check']]);

$page = new Page(['slug' => 'anchor-check']);
$block = static fn (string $id, array $content = [], ?Page $parent = null): Block => new Block([
    'id' => $id,
    'type' => 'text',
    'parent' => $parent ?? $page,
    'content' => $content,
]);
$expect = static function (Block $block, string $expected, string $fieldName = 'id'): string {
    $actual = $block->anchorId($fieldName);

    if ($actual !== $expected || preg_match('/\A[a-z][a-z0-9-]*\z/', $actual) !== 1) {
        throw new RuntimeException("Expected '$expected', got '$actual'.");
    }

    return $actual;
};

$intro = $block('intro-1', ['id' => 'Intro', 'title' => 'Other title']);
$expect($intro, 'intro');
$expect($intro, 'intro');
$expect($intro, 'intro', 'title');
$expect(clone $intro, 'intro');
$expect($block('intro-1', ['id' => 'Changed']), 'intro');
$expect($block('intro-1', ['id' => 'Changed'], new Page(['slug' => 'anchor-check'])), 'intro');
$expect($block('intro-2', ['id' => 'Intro']), 'intro-2');
$expect($block('intro-3', ['id' => 'Intro-3']), 'intro-3');
$expect($block('intro-4', ['id' => 'Intro']), 'intro-4');
$expect($block('intro-5', ['id' => 'Intro']), 'intro-5');
$expect($block('intro-1', ['id' => 'Intro'], new Page(['slug' => 'nested-check'])), 'intro-6');
$expect($intro, 'intro');

$custom = $block('custom', ['id' => 'Wrong field', 'anchor' => 'Chosen field']);
$expect($custom, 'chosen-field', 'anchor');
$expect($custom, 'chosen-field');

foreach ([
    ['Český nadpis', 'cesky-nadpis'],
    ['A/B # C?D', 'a-b-c-d'],
    ['123 Title', 'b-123-title'],
    ['b-123-title', 'b-123-title-2'],
    ['0', 'b-0'],
    ['100% & more', 'b-100-more'],
] as $index => [$value, $expected]) {
    $item = $block('slug-' . $index, ['id' => $value]);
    $expect($item, $expected);

    if ($item->content()->get('id')->value() !== $value) {
        throw new RuntimeException('Anchor generation modified the content field.');
    }
}

$uuid = 'f5d20b51-48a4-4d95-9bf5-0ce34e6f247b';
$fallback = 'b-614bcb8d5314';
$expect($block($uuid), $fallback);

foreach (['', '   ', '!!!', '[]'] as $index => $value) {
    $expect(
        $block($uuid, ['id' => $value], new Page(['slug' => 'empty-' . $index])),
        $fallback . '-' . ($index + 2),
    );
}

if (strlen($fallback) > strlen($uuid) / 2) {
    throw new RuntimeException('Fallback is not at least 50% shorter than the UUID.');
}

$expect($block('explicit-fallback', ['id' => $fallback]), $fallback . '-6');
$expect($block('reserved-fallback', ['id' => 'b-d278c3d9c07a']), 'b-d278c3d9c07a');
$expect($block('f5d20b51-48a4-4d95-9bf5-0ce34e6f247c'), 'b-d278c3d9c07a-2');

echo "anchorId checks passed; stable fallback: $fallback\n";
