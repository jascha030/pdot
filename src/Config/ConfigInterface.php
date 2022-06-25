<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

interface ConfigInterface
{
    /**
     * The origin of the config object.
     * E.g. filename for confurations parsed from a file.
     */
    public function getOrigin(): string;

    /**
     * If an incomplete config should be merged with other found configurations by default.
     * E.g. when a Configuration is created based on Environment variables,
     * and a config file is also present should the resolver attempt to merge the configurations.
     */
    public function preferMerge(): bool;

    public function getDotDirs(): array;

    public function getDestination(): string;

    public function getAddDots(): bool;

    public function getUndottedPatterns(): ?array;

    public function getIgnoredPatterns(): array|string;

    /**
     * Get the configuration as array of $key => $value pairs.
     */
    public function getRaw(): array;
}
