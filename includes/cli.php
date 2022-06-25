<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles;

function home(): string
{
    return $_SERVER['HOME'];
}

function defaultConfigPath(): string
{
    return home() . '/.config/pdot';
}
