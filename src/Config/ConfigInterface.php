<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

interface ConfigInterface
{
    public function getDotDirs(): array;

    public function getCachePath(): string;

    public function getAddDots(): bool;

    public function getIgnoredPatterns(): array|string;
}
