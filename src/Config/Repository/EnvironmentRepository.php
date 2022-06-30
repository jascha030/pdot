<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository;

use Jascha030\Dotfiles\Config\Config;
use Jascha030\Dotfiles\Config\ConfigInterface;

class EnvironmentRepository extends ConfigRepository
{
    private const ENVIRONMENT_KEY_MAP = [
        'PDOT_DESTINATION'      => 'destination',
        'PDOT_DOTFILE_DIRS'     => 'dotDirs',
        'PDOT_ADD_DOTS'         => 'addDots',
        'PDOT_UNDOT_PATTERNS'   => 'undottedPatterns',
        'PDOT_IGNORED_PATTERNS' => 'ignoredPatterns',
    ];

    /**
     * {@inheritDoc}
     */
    public static function getName(): string
    {
        return 'environment';
    }

    /**
     * {@inheritDoc}
     */
    public static function getPriority(): int
    {
        return ConfigRepository::PRIO_HIGH;
    }

    /**
     * {@inheritDoc}
     */
    public static function getDescription(): string
    {
        return 'Uses environment variables to configure behaviour of the `pdot up` command.';
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(): null|ConfigInterface
    {
        $resolvedVariables = $this->getEnvironmentVariables();

        if (0 === count($resolvedVariables)) {
            return null;
        }

        return Config::create($resolvedVariables);
    }

    private function getEnvironmentVariables(): array
    {
        $environmentVariables = [];

        foreach (static::ENVIRONMENT_KEY_MAP as $option => $property) {
            $environmentVariables[$property] = $_SERVER[$option] ?? null;
        }

        return array_filter($environmentVariables);
    }
}
