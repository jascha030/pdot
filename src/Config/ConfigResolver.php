<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

use Jascha030\Dotfiles\Config\Repository\ConfigRepositoryInterface;

class ConfigResolver
{
    private array $repositories;

    private int $count;

    /**
     * @var array[]
     */
    private array $resolvedInstances;

    public function __construct()
    {
        $this->repositories = [];
    }

    public function addRepository(ConfigRepositoryInterface $repository): static
    {
        if (! isset($this->repositories[$repository->getPriority()])) {
            $this->repositories[$repository->getPriority()] = [];
        }

        $this->repositories[$repository->getPriority()][] = $repository;

        ksort($this->repositories);

        return $this;
    }

    public function resolve(bool $stopOnMatch = false): static
    {
        $this->count = 0;

        foreach ($this->repositories as $prio => $repositories) {
            $this->resolvedInstances[$prio] = [];

            /** @var ConfigRepositoryInterface $repository */
            foreach ($repositories as $repository) {
                if (null === $config = $repository->resolve()) {
                    continue;
                }

                ++$this->count;

                if (is_array($config)) {
                    $this->resolvedInstances[$prio] += $config;

                    continue;
                }

                $this->resolvedInstances[$prio][] = $config;

                if (true === $stopOnMatch) {
                    return $this;
                }
            }
        }

        return $this;
    }

    public function foundMultiple(): bool
    {
        return ($this->count ?? 0) > 1;
    }

    /**
     * @todo: option to select config from list of found configurations.
     * @todo: allow merging configs based on prio.
     */
    public function getResolvedConfig(): ConfigInterface
    {
        // todo: options on how to handle multiple instances.
        $highestPrio = reset($this->resolvedInstances);

        return reset($highestPrio);
    }
}
