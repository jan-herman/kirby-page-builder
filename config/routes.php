<?php

use Kirby\Cms\App;

return function (App $kirby) {
    return [
        [
            'pattern' => $kirby->option('jan-herman.page-builder.blockLibrary.slug') . '(:all)',
            'language' => '*',
            'action' => function () {
                return false;
            }
        ],
    ];
};
