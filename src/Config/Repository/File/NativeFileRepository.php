<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use Jascha030\Dotfiles\Config\ConfigInterface;

use function sprintf;

final class NativeFileRepository extends ConfigFileRepository
{
    public static function getName(): string
    {
        return 'native-file';
    }

    public static function getDescription(): string
    {
        return sprintf('Simple php file that returns an object implementing "%s"', ConfigInterface::class);
    }

    public static function getAllowedPatterns(): array|string
    {
        return '/\.?pdot(\..*)?\.php$/';
    }
}
