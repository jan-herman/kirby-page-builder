<?php

return [
    'pageBuilderBlocks' => function () {
        return page_builder()->pageBlocks($this);
    },
    'pageBuilderBlockDefinitions' => function () {
        return page_builder()->pageBlockDefinitions($this);
    }
];
