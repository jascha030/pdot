<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

use Generator;
use ReflectionObject;
use function Jascha030\Dotfiles\home;

class Config implements ConfigInterface
{
    public function __construct(
        private string $origin,
        private null|array|string $dotDirs = '/.dotfiles',
        private ?bool $addDots = true,
        private ?array $undottedPatterns = null,
        private null|array|string $ignoredPatterns = ['.gitignore', '.git', 'README*', 'LICENSE*'],
        private bool $preferMerge = true,
    ) {
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function preferMerge(): bool
    {
        return $this->preferMerge;
    }

    public function getDestination(): string
    {
        return home();
    }

    public function getDotDirs(): array
    {
        return $this->dotDirs;
    }

    public function getAddDots(): bool
    {
        return $this->addDots ?? true;
    }

    public function getUndottedPatterns(): ?array
    {
        return $this->undottedPatterns;
    }

    public function getIgnoredPatterns(): array|string
    {
        return $this->ignoredPatterns;
    }

    public function setDotDirs(array|string|null $dotDirs): Config
    {
        $this->dotDirs = $dotDirs;

        return $this;
    }

    public function setAddDots(?bool $addDots): Config
    {
        $this->addDots = $addDots;

        return $this;
    }

    public function setIgnoredPatterns(array|string|null $ignoredPatterns): Config
    {
        $this->ignoredPatterns = $ignoredPatterns;

        return $this;
    }

    public function getRaw(): array
    {
        return iterator_to_array($this->rawValueGenerator());
    }

    private function rawValueGenerator(): Generator
    {
        $properties = (new ReflectionObject($this))->getProperties();

        foreach ($properties as $reflectionProperty) {
            $name = $reflectionProperty->getName();

            yield $name => $this->{$name};
        }
    }
}
