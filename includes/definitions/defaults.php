<?php

declare(strict_types=1);

return [
    'default.home'            => $_SERVER['HOME'],
    'default.dotdir'          => '.dotfiles',
    'default.patterns.ignore' => [
        '*.git*',
        '*README*',
        '*LICENSE*',
    ],
    'default.patterns.undot' => [
    ],
];
