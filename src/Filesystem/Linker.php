<?php
/** @noinspection PhpInternalEntityUsedInspection */

declare(strict_types=1);

namespace Jascha030\Dotfiles\Filesystem;

use Generator;
use Jascha030\Dotfiles\Config\ConfigInterface;
use Jascha030\Dotfiles\Finder\Finder;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class Linker
{
    private array $map;

    private Filesystem $fs;

    private array $linkedFiles;

    private array $errors;

    private function __construct(private ConfigInterface $config)
    {
        $this->map         = [];
        $this->linkedFiles = [];
        $this->errors      = [];
    }

    final public function getErrors(): ?array
    {
        return ! empty($this->errors)
            ? $this->errors
            : null;
    }

    final public function getLinkedFiles(): ?array
    {
        return ! empty($this->linkedFiles)
            ? $this->linkedFiles
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
                $this->errors[$originPath] = $exception->getMessage();

                continue;
            }

            $this->linkedFiles[$originPath] = $destinationPath;
        }

        return $this;
    }

    public function getQueue(): Generator
    {
        foreach ($this->getMap() as $filePath => $desinationPath) {
            if ($this->exists($desinationPath)) {
                continue;
            }

            yield $filePath => $desinationPath;
        }
    }

    public static function create(ConfigInterface $config): static
    {
        return new static($config);
    }

    private function getMap(): array
    {
        if (! empty($this->map)) {
            return $this->map;
        }

        return $this->createMap()->getMap();
    }

    private function createMap(): static
    {
        $finder = Finder::dotfileFinder($this->config);

        /** @var SplFileInfo $file */
        foreach ($finder->getIterator() as $file) {
            $this->map[$file->getRealPath()] = $this->getDestinationPath($file);
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
