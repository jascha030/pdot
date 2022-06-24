<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository;

use Jascha030\Dotfiles\Config\Parser\ConfigFileParserInterface;

interface ConfigFileRepositoryInterface extends ConfigRepositoryInterface
{
    /**
     * A (or multiple) pattern(s) to find a valid config file.
     */
    public function getPatterns(): string|array;

    /**
     * @return ConfigFileParserInterface[]
     */
    public function getAvailableParsers(): array;

    /**
     * Path to the default config of this format.
     */
    public function getStubPath(): string;
}
