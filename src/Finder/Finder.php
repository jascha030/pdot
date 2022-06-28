<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Finder;

use Jascha030\Dotfiles\Config\ConfigInterface;
use function Jascha030\Dotfiles\defaultConfigPath;
use function Jascha030\Dotfiles\home;

class Finder extends \Symfony\Component\Finder\Finder
{
    public static function dotfileFinder(ConfigInterface $config): static
    {
        return static::create()->setConfig($config);
    }

    public static function configFinder(): static
    {
        return static::create()
            ->ignoreDotFiles(false)
            ->files();
    }

    private function setConfig(ConfigInterface $config): static
    {
        return $this
            ->ignoreDotFiles(false)
            ->ignoreVCS(true)
            ->in($config->getDotDirs())
            ->notName($config->getIgnoredPatterns())
            ->directories()
            ->files();
    }
}
