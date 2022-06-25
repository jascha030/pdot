<?php

/**
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

use Exception;
use Generator;
use ReflectionObject;

class Config implements ConfigInterface
{
    private ?string $origin;

    private ?string $destination;

    private null|array|string $dotDirs;

    private ?bool $addDots;

    private ?array $undottedPatterns;

    private null|array|string $ignoredPatterns;

    private function __construct()
    {
        $this->origin           = null;
        $this->destination      = null;
        $this->dotDirs          = null;
        $this->addDots          = null;
        $this->undottedPatterns = null;
        $this->ignoredPatterns  = null;
    }

    public static function create(array $values): static
    {
        $config = new static();

        foreach ($values as $key => $value) {
            $accessor = 'set' . ucfirst($key);

            if (method_exists(static::class, $accessor)) {
                try {
                    $config->{$accessor}($value);
                } catch (Exception) {
                    // todo: Relay this to user during command execution.
                    continue;
                }
            }
        }

        return $config;
    }

    /**
     * {@inheritDoc}
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    /**
     * {@inheritDoc}
     */
    public function getDotDirs(): null|string|array
    {
        return $this->dotDirs;
    }

    /**
     * {@inheritDoc}
     */
    public function getDestination(): ?string
    {
        return $this->destination;
    }

    /**
     * {@inheritDoc}
     */
    public function getAddDots(): ?bool
    {
        return $this->addDots;
    }

    /**
     * {@inheritDoc}
     */
    public function getUndottedPatterns(): ?array
    {
        return $this->undottedPatterns;
    }

    /**
     * {@inheritDoc}
     */
    public function getIgnoredPatterns(): ?array
    {
        return $this->ignoredPatterns;
    }

    public function setOrigin(?string $origin): Config
    {
        $this->origin = $origin;

        return $this;
    }

    public function setDestination(?string $destination): Config
    {
        $this->destination = $destination;

        return $this;
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

    public function setUndottedPatterns(?array $undottedPatterns): Config
    {
        $this->undottedPatterns = $undottedPatterns;

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
