<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use Jascha030\Dotfiles\Config\ConfigInterface;
use Jascha030\Dotfiles\Config\Parser\ConfigFileParserInterface;
use Jascha030\Dotfiles\Config\Repository\ConfigRepositoryInterface;
use Symfony\Component\Finder\Finder;

interface ConfigFileRepositoryInterface extends ConfigRepositoryInterface
{
    /**
     * Pattern compatible with `\Symfony\Component\Finder\Finder::name()` method.
     * Accepts globs, strings, regexes or an array of globs, strings or regexes.
     *
     * @see https://symfony.com/doc/current/components/finder.html#file-name
     */
    public static function getAllowedPatterns(): array|string;

    /**
     * Path to a stub (template file) containing the default config file of this format,
     * if this is not appliccable for the specific type, null may be returned.
     */
    public static function getStubPath(): ?string;

    /**
     * Check if given filename matches a pattern provided by the repository.
     */
    public function isMatch(string $filePath): bool;

    /**
     * Finder object configured to find the specified type of config file(s).
     */
    public function getFinder(): Finder;

    /**
     * Parser object, able to parse specified Config filetype to an object implementing `ConfigInterface`.
     *
     * @see ConfigFileParserInterface
     * @see ConfigInterface
     */
    public function getParser(): ConfigFileParserInterface;
}
