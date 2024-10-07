<?php

$block_page = $block->nested_block()->toPage();

if (!$block_page || (!$kirby->user() && !$block_page->isListed())) {
    return;
}

$page_builder_field = $block_page->page_builder();

snippet('page-builder', ['field' => $page_builder_field, 'is_nested' => true]);
