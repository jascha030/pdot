<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Finder;

use Jascha030\Dotfiles\Config\ConfigInterface;

class Finder extends \Symfony\Component\Finder\Finder
{
    public static function dotfileFinder(ConfigInterface $config): static
    {
        return static::create()->setConfig($config);
    }

    /**
     * @todo: implement method to setup for finding RC Files.
     */
    public static function configFinder(): static
    {
        return static::create();
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
