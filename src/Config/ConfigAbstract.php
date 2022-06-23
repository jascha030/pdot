<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

use function Jascha030\Dotfiles\home;

abstract class ConfigAbstract implements ConfigInterface
{
    public function __construct(
        private null|array|string $dotDirs = '/.dotfiles',
        private ?string $cachePath = null,
        private ?bool $addDots = true,
        private null|array|string $ignoredPatterns = null
    ) {
    }

    public function getDestination(): string
    {
        return home();
    }

    public function getDotDirs(): array
    {
        return $this->dotDirs;
    }

    public function getCachePath(): string
    {
        return $this->cachePath ?? getenv('HOME') . '/';
    }

    public function getAddDots(): bool
    {
        return $this->addDots ?? true;
    }

    public function getIgnoredPatterns(): array|string
    {
        return $this->ignoredPatterns;
    }
}
