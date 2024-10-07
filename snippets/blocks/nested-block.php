<?php

if ($block->nested_block()->isEmpty()) {
    return;
}

$block_page = $block->nested_block()->toPage();
$page_builder_field = $block_page->page_builder();

snippet('page-builder', ['field' => $page_builder_field]);
