<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

use Illuminate\Support\Arr;
use Illuminate\Support\LazyCollection;
use InvalidArgumentException;
use Jascha030\Dotfiles\Config\Repository\File\ConfigFileRepository;
use Jascha030\Dotfiles\Config\Repository\File\ConfigFileRepositoryInterface;
use Jascha030\Dotfiles\Config\Util\RegexValidatorTrait;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use SplFileInfo;

final class ConfigResolver
{
    use RegexValidatorTrait;

    private array $priorities;

    /**
     * @var array[]
     */
    private array $resolvedConfigurations;

    private int $count;

    /**
     * @param array<int,class-string> $repositories
     */
    public function __construct(private ContainerInterface $container, private array $repositories)
    {
        $this->count = 0;

        foreach ($repositories as $repository) {
            $this->addRepository($repository);
        }
    }

    /**
     * @param class-string<ConfigFileRepository> $repository
     */
    public function addRepository(string $repository): ConfigResolver
    {
        if (! is_subclass_of($repository, ConfigFileRepository::class)) {
            return $this;
        }

        if (! isset($this->repositories[$repository::getPriority()])) {
            $this->priorities[$repository::getPriority()] = [];
        }

        $this->priorities[$repository::getPriority()][] = $repository;

        ksort($this->priorities);

        return $this;
    }

    public function getConfig(?string $path = null): ?ConfigInterface
    {
        if (null !== $path) {
            return $this->getByPath($path);
        }

        $this->resolveConfiguration();

        return match (true) {
            $this->foundMultiple() => $this->mergeConfigurations(),
            $this->foundAny()      => Arr::first(Arr::first($this->resolvedConfigurations)),
            default                => null,
        };
    }

    public function resolveConfiguration(): ConfigResolver
    {
        $this->count = 0;

        foreach ($this->priorities as $prio => $repositories) {
            foreach ($repositories as $repository) {
                try {
                    $config = $this->container->get($repository)->resolve();
                } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
                    continue;
                }

                if (null === $config) {
                    continue;
                }

                if (! isset($this->resolvedConfigurations[$prio])) {
                    $this->resolvedConfigurations[$prio] = [];
                }

                $this->resolvedConfigurations[$prio][] = $config;

                ++$this->count;
            }
        }

        return $this;
    }

    public function getByPath(string $path): ?ConfigInterface
    {
        if (! file_exists($path)) {
            throw new InvalidArgumentException("Could not find file: \"{$path}\".");
        }

        $info = new SplFileInfo($path);
        foreach ($this->repositories as $repository) {
            if (! is_subclass_of($repository, ConfigFileRepositoryInterface::class)) {
                continue;
            }

            if ($this->isMatch($info->getFilename(), $repository::getAllowedPatterns())) {
                try {
                    return $this->container
                        ->get($repository)
                        ->getParser()
                        ->parse($info->getRealPath());
                } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
                    // Todo: Understandable exceptions.
                    continue;
                }
            }
        }

        return null;
    }

    public function foundMultiple(): bool
    {
        return $this->count > 1;
    }

    public function foundAny(): bool
    {
        return $this->count > 0;
    }

    private function isMatch(string $filename, array|string $patterns): bool
    {
        if (\is_string($patterns)) {
            return preg_match($this->toRegex($patterns), $filename) > 0;
        }

        return collect($patterns)->contains(fn (string $pattern) => $this->isMatch(
            $filename,
            $pattern
        ));
    }

    private function mergeConfigurations(): ConfigInterface
    {
        return Config::create(
            new LazyCollection(function () {
                yield from new RawConfigIterator($this->resolvedConfigurations);
            })
        );
    }
}
