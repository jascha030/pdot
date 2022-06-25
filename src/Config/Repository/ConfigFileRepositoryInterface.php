<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository;

use Jascha030\Dotfiles\Config\Parser\ConfigFileParserInterface;
use Jascha030\Dotfiles\Finder\Finder;

interface ConfigFileRepositoryInterface extends ConfigRepositoryInterface
{
    public function getFinder(): Finder;

    public function getParser(): ConfigFileParserInterface;

    /**
     * Path to the default config of this format.
     */
    public function getStubPath(): string;
}
