<?php

/**
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

use Exception;
use Generator;
use Illuminate\Contracts\Support\Arrayable;
use ReflectionObject;

use function Jascha030\Dotfiles\home;
use function sprintf;

class Config implements ConfigInterface, Arrayable
{
    private const IGNORE_ALWAYS = [
        '.git*',
        '*README.*',
        '*LICENSE*',
    ];

    private ?string $origin;

    private ?string $destination;

    private array|string|null $dotDirs;

    private ?bool $addDots;

    private ?array $undottedPatterns;

    private array|string|null $ignoredPatterns;

    private function __construct()
    {
        $this->origin           = null;
        $this->destination      = sprintf('%s/.dotfiles', home());
        $this->dotDirs          = null;
        $this->addDots          = null;
        $this->ignoredPatterns  = null;
        $this->undottedPatterns = null;
    }

    public static function create(?iterable $values = null): static
    {
        if (null === $values) {
            return new static();
        }

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

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function getDotDirs(): string|array|null
    {
        return $this->dotDirs ?? home() . '/.dotfiles';
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function getAddDots(): ?bool
    {
        return $this->addDots;
    }

    public function getUndottedPatterns(): ?array
    {
        return $this->undottedPatterns;
    }

    public function getIgnoredPatterns(): ?array
    {
        return $this->ignoredPatterns;
    }

    public function setOrigin(?string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function setDestination(?string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function setDotDirs(array|string|null $dotDirs): self
    {
        $this->dotDirs = $dotDirs;

        return $this;
    }

    public function setAddDots(?bool $addDots): self
    {
        $this->addDots = $addDots;

        return $this;
    }

    public function setUndottedPatterns(?array $undottedPatterns): self
    {
        $this->undottedPatterns = $undottedPatterns;

        return $this;
    }

    public function setIgnoredPatterns(?array $ignoredPatterns): self
    {
        $this->ignoredPatterns = array_merge(self::IGNORE_ALWAYS, $ignoredPatterns);

        return $this;
    }

    public function toArray(): array
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
