<?php

declare(strict_types=1);

use Jascha030\Dotfiles\Config\Config;

return Config::create()
    ->setDotDirs(__DIR__ . '/.dotfiles')
    ->setDestination(__DIR__)
    ->setUndottedPatterns(['undotmepls'])
    ->setIgnoredPatterns(['ignoremepls']);
