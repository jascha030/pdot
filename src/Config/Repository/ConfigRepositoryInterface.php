<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository;

use Jascha030\Dotfiles\Config\ConfigInterface;

interface ConfigRepositoryInterface
{
    /**
     * Describes the Configuration type.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Create an instance of ConfigInterface.
     *
     * @return ConfigInterface
     */
    public function resolve(): ConfigInterface;
}
