<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles;

use Jascha030\CLI\Shell\ShellInterface;
use Jascha030\Dotfiles\Config\ConfigInterface;
use Jascha030\Dotfiles\Finder\Finder;

class Application
{
    private Finder $finder;

    public function __construct(
        private ConfigInterface $config,
        private ShellInterface  $shell
    )
    {
        $this->finder = Finder::create()->setConfig($this->config);
    }
}
