<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use Jascha030\Dotfiles\Config\ConfigInterface;

final class NativeFileRepository extends ConfigFileRepository
{
    /**
     * {@inheritDoc}
     */
    public static function getName(): string
    {
        return 'native-file';
    }

    /**
     * {@inheritDoc}
     */
    public static function getDescription(): string
    {
        return sprintf('Simple php file that returns an object implementing "%s"', ConfigInterface::class);
    }

    /**
     * {@inheritDoc}
     */
    public static function getAllowedPatterns(): array|string
    {
        return '/\.?pdot(\..*)?\.php$/';
    }
}
