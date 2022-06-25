<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository;

use Jascha030\Dotfiles\Config\ConfigInterface;

abstract class ConfigRepository implements ConfigRepositoryInterface
{
    public const PRIO_HIGH = 10;

    public const PRIO_NORMAL = 50;

    public function __construct(
        private string $name,
        private string $description
    ) {
    }

    /**
     * {@inheritDoc}
     */
    abstract public function resolve(): null|array|ConfigInterface;

    /**
     * {@inheritDoc}
     */
    public function getPriority(): int
    {
        return self::PRIO_NORMAL;
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