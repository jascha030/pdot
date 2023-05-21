<?php

/** @noinspection PhpInternalEntityUsedInspection */

declare(strict_types=1);

namespace Jascha030\Dotfiles\Filesystem;

use Generator;
use Illuminate\Support\Collection;
use Jascha030\Dotfiles\Config\ConfigInterface;
use Jascha030\Dotfiles\Finder\Finder;
use LogicException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\SplFileInfo;

class Linker
{
    private Collection $map;

    private Filesystem $fs;

    private Collection $linkedFiles;

    private Collection $errors;

    private function __construct(private ConfigInterface $config)
    {
        $this->map         = new Collection([]);
        $this->linkedFiles = new Collection([]);
        $this->errors      = new Collection([]);
    }

    final public function getErrors(): ?array
    {
        return ! $this->errors->isEmpty()
            ? $this->errors->toArray()
            : null;
    }

    final public function getLinkedFiles(): ?array
    {
        return ! $this->linkedFiles->isEmpty()
            ? $this->linkedFiles->toArray()
            : null;
    }

    public function linkFiles(): static
    {
        if (! isset($this->fs)) {
            $this->fs = new Filesystem();
        }

        foreach ($this->getQueue() as $originPath => $destinationPath) {
            try {
                $this->fs->symlink($originPath, $destinationPath);
            } catch (IOException $exception) {
                $this->errors->put($originPath, $exception);

                continue;
            }

            $this->linkedFiles[$originPath] = $destinationPath;
        }

        return $this;
    }

    /**
     * @return Generator
     *
     * @throws DirectoryNotFoundException
     * @throws LogicException
     */
    public function getQueue(): Generator
    {
        foreach ($this->getMap() as $filePath => $destinationPath) {
            if ($this->exists($destinationPath)) {
                continue;
            }

            yield $filePath => $destinationPath;
        }
    }

    public static function create(ConfigInterface $config): static
    {
        return new static($config);
    }

    private function getMap(): Collection
    {
        if (! $this->map->isEmpty()) {
            return $this->map;
        }

        return $this->createMap()->getMap();
    }

    private function createMap(): static
    {
        $finder = Finder::dotfileFinder($this->config);

        /** @var SplFileInfo $file */
        foreach ($finder->getIterator() as $file) {
            $this->map->put(
                $file->getRealPath(),
                $this->getDestinationPath($file)
            );
        }

        return $this;
    }

    private function exists(string $linkPath): bool
    {
        return ! file_exists($linkPath);
    }

    private function getDestinationPath(SplFileInfo $fileInfo): string
    {
        return str_replace(
            $this->config->getDotDirs(),
            $this->config->getDestination(),
            $fileInfo->getRealPath()
        );
    }
}
