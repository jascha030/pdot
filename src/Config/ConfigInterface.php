<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

interface ConfigInterface
{
    /**
     * The origin of the config object.
     * E.g. filename for confurations parsed from a file.
     */
    public function getOrigin(): ?string;

    /**
     * Dotfile directories to track, defaults to `$HOME/.dotfiles`.
     */
    public function getDotDirs(): null|string|array;

    /**
     * Root of the destination directory, defaults to `$HOME`.
     */
    public function getDestination(): ?string;

    /**
     * Should paths be prefixed with a `.` (dot), defaults to `true`.
     */
    public function getAddDots(): ?bool;

    /**
     * If AddDots is set to `true` add an optional list of patterns of which dots shall be omitted.
     */
    public function getUndottedPatterns(): ?array;

    /**
     * List of patterns to ignore when using the `pdot up` command.
     * Paths that are ignored by default:
     * - `*.git*`
     * - `README*`
     * - `LICENSE`.
     * - Any type of pdot configuration file.
     */
    public function getIgnoredPatterns(): ?array;
}
