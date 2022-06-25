<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository;

use Iterator;
use Jascha030\Dotfiles\Config\ConfigInterface;

abstract class ConfigRepository implements ConfigRepositoryInterface
{
    public const PRIO_HIGH = 10;

    public const PRIO_NORMAL = 50;

    public function __construct(
        private string $name,
        private string $description,
        private int $prio = self::PRIO_NORMAL,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    abstract public function resolve(): null|Iterator|ConfigInterface;

    /**
     * {@inheritDoc}
     */
    public function getPriority(): int
    {
        return $this->prio;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
