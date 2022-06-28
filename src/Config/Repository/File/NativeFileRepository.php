<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use Jascha030\Dotfiles\Config\Parser\ConfigFileParserInterface;
use Jascha030\Dotfiles\Finder\Finder;

class NativeFileRepository extends ConfigFileRepository
{

    /**
     * {@inheritDoc}
     */
    public function getStubPath(): ?string
    {
        return null;
    }

    public function getAllowedPatterns(): array
    {
        return [
            '.pdot.*.php',
            '.pdot.php',
        ];
    }
}
