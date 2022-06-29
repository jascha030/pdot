<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use Jascha030\Dotfiles\Config\ConfigInterface;

class NativeFileRepository extends ConfigFileRepository
{
    public function __construct()
    {
        parent::__construct('native-file', sprintf(
            'Simple php file that returns an object implementing "%s"',
            ConfigInterface::class
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getAllowedPatterns(): array|string
    {
        return '/\.?pdot(\..*)?\.php$/';
    }

    /**
     * {@inheritDoc}
     */
    public function getStubPath(): ?string
    {
        return null;
    }
}
