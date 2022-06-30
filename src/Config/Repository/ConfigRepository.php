<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository;

use Iterator;
use Jascha030\Dotfiles\Config\ConfigInterface;

abstract class ConfigRepository implements ConfigRepositoryInterface
{
    public const PRIO_HIGH = 10;

    public const PRIO_NORMAL = 50;

    /**
     * {@inheritDoc}
     */
    abstract public function resolve(): null|Iterator|ConfigInterface;

    /**
     * {@inheritDoc}
     */
    public static function getPriority(): int
    {
        return self::PRIO_NORMAL;
    }
}
