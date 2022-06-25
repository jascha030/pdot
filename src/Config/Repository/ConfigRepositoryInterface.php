<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository;

use Iterator;
use Jascha030\Dotfiles\Config\ConfigInterface;

interface ConfigRepositoryInterface
{
    /**
     * The priority a config type gets, when multiple config sources are found.
     */
    public function getPriority(): int;

    /**
     * Short descriptive name of the Configuration type.
     */
    public function getName(): string;

    /**
     * Extended discription of the Configuration type.
     */
    public function getDescription(): string;

    /**
     * Create an instance of ConfigInterface.
     *
     * @return null|ConfigInterface|ConfigInterface[]
     */
    public function resolve(): null|Iterator|ConfigInterface;
}
