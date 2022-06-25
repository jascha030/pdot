<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use Jascha030\Dotfiles\Config\Parser\ConfigFileParserInterface;
use Jascha030\Dotfiles\Config\Repository\ConfigRepositoryInterface;
use Jascha030\Dotfiles\Finder\Finder;

interface ConfigFileRepositoryInterface extends ConfigRepositoryInterface
{
    public function getFinder(): Finder;

    public function getParser(): ConfigFileParserInterface;

    /**
     * Path to a stub containing a default config file of this format.
     */
    public function getStubPath(): ?string;
}
