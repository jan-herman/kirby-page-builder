<?php

if (isset($field) && $field->isNotEmpty()) {
    foreach ($field->toBlocks() as $block) {
        echo $block;
    }
}
