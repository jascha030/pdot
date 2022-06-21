<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Finder;

use Jascha030\Dotfiles\Config\ConfigInterface;

class Finder extends \Symfony\Component\Finder\Finder
{
    public function setConfig(ConfigInterface $config): static
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
