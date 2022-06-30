<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

use InvalidArgumentException;
use Jascha030\Dotfiles\Config\Repository\ConfigRepositoryInterface;
use Jascha030\Dotfiles\Config\Repository\File\ConfigFileRepository;
use Jascha030\Dotfiles\Config\Repository\File\ConfigFileRepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use SplFileInfo;
use Symfony\Component\Finder\Glob;

final class ConfigResolver
{
    private array $priorities;

    /**
     * @var array[]
     */
    private array $resolvedConfigurations;

    public function __construct(private ContainerInterface $container, private array $repositories)
    {
        foreach ($repositories as $repository) {
            $this->addRepository($repository);
        }
    }

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

    public function getFromInput(string $input): ?ConfigInterface
    {
        if (! file_exists($input)) {
            throw new InvalidArgumentException("Could not find file: \"{$input}\".");
        }

        $info = new SplFileInfo($input);

        foreach ($this->repositories as $repositories) {
            foreach ($repositories as $repository) {
                if (! is_subclass_of($repository, ConfigFileRepositoryInterface::class)) {
                    continue;
                }

                if ($this->isMatch($info->getFilename(), $repository::getAllowedPatterns())) {
                    try {
                        return $this->container->get($repository)->getParser()->parse($info->getRealPath());
                    } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
                        // Todo: Understandible exceptions.
                        continue;
                    }
                }
            }
        }

        return null;
    }

    public function resolve(bool $stopOnMatch = false): ConfigResolver
    {
        foreach ($this->priorities as $prio => $repositories) {
            $this->resolvedConfigurations[$prio] = [];

            /** @var ConfigRepositoryInterface $repository */
            foreach ($repositories as $repository) {
                try {
                    $config = $this->container->get($repository)->resolve();
                } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
                    continue;
                }

                if (null === $config) {
                    continue;
                }

                ++$this->count;

                $this->resolvedConfigurations[$prio][] = $config;

                if (true === $stopOnMatch) {
                    return $this;
                }
            }
        }

        return $this;
    }

    public function foundMultiple(): bool
    {
        return count($this->resolvedConfigurations) > 1;
    }

    /**
     * @todo: option to select config from list of found configurations.
     * @todo: allow merging configs based on prio.
     */
    public function getConfig(): ConfigInterface
    {
        // todo: options on how to handle multiple instances.
        $highestPrio = reset($this->resolvedConfigurations);

        return reset($highestPrio);
    }

    private function isMatch(string $filename, array|string $patterns): bool
    {
        if (is_string($patterns)) {
            return preg_match($this->toRegex($patterns), $filename) > 0;
        }

        foreach ($patterns as $pattern) {
            if (! $this->isMatch($filename, $pattern)) {
                continue;
            }

            return true;
        }

        return false;
    }

    private function toRegex(string $str): string
    {
        return $this->isRegex($str) ? $str : Glob::toRegex($str);
    }

    private function isRegex(string $str): bool
    {
        $availableModifiers = 'imsxuADU';

        if (\PHP_VERSION_ID >= 80200) {
            $availableModifiers .= 'n';
        }

        if (preg_match('/^(.{3,}?)[' . $availableModifiers . ']*$/', $str, $m)) {
            $start = substr($m[1], 0, 1);
            $end   = substr($m[1], -1);

            if ($start === $end) {
                return ! preg_match('/[*?[:alnum:] \\\\]/', $start);
            }

            foreach ([['{', '}'], ['(', ')'], ['[', ']'], ['<', '>']] as $delimiters) {
                if ($start === $delimiters[0] && $end === $delimiters[1]) {
                    return true;
                }
            }
        }

        return false;
    }
}
