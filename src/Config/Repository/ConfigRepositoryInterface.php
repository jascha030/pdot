<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

interface RepositoryInterface
{
    public function getName(): string;

    public function getPattern(): string;
}
