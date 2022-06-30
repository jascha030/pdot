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
use function Jascha030\Dotfiles\home;

class Linker
{
    private string|array $dotDir;

    private array $map;

    private Filesystem $fs;

    private array $linked;

    private array $errors;

    public function __construct(private ConfigInterface $config)
    {
        $this->map    = [];
        $this->linked = [];
        $this->errors = [];

        $this->fs     = new Filesystem();
        $this->dotDir = $this->config->getDotDirs() ?? home() . '/.dotfiles';
    }

    public function linkFiles(): static
    {
        foreach ($this->getQueue() as $originPath => $destinationPath) {
            try {
                $this->fs->symlink($originPath, $destinationPath);
            } catch (IOException $exception) {
                $this->errors[$originPath] = $exception->getMessage();
                continue;
            }

            $this->linked[$originPath] = $destinationPath;
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
        return str_replace($this->dotDir, $this->config->getDestination(), $fileInfo->getRealPath());
    }
}
