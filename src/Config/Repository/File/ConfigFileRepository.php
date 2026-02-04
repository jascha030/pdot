<?php

/**
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use ArrayIterator;
use Illuminate\Support\Collection;
use Iterator;
use Jascha030\Dotfiles\Config\ConfigInterface;
use Jascha030\Dotfiles\Config\Parser\ConfigFileParserInterface;
use Jascha030\Dotfiles\Config\Repository\ConfigRepository;
use Jascha030\Dotfiles\Finder\Finder;
use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;

use function Jascha030\Dotfiles\defaultConfigPath;
use function Jascha030\Dotfiles\home;

abstract class ConfigFileRepository extends ConfigRepository implements ConfigFileRepositoryInterface
{
    private ConfigFileParserInterface $parser;

    private iterable $searchDirs;

    public function __construct()
    {
        $this->searchDirs = new Collection([home(), defaultConfigPath()]);
    }

    public static function getStubPath(): ?string
    {
        return null;
    }

    /**
     * @noinspection PhpUnused
     */
    final public function setParser(ConfigFileParserInterface $parser): static
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @throws RuntimeException
     */
    public function getParser(): ConfigFileParserInterface
    {
        return $this->parser ?? throw self::parserException();
    }

    /**
     * @param array<int,mixed> $directories
     */
    public function setSearchDirs(array $directories): self
    {
        $this->searchDirs = $directories;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getSearchDirs(): ?iterable
    {
        return $this->searchDirs;
    }

    public function getFinder(): Finder
    {
        return Finder::configFinder()
            ->depth('== 0')
            ->in($this->getSearchDirs()?->toArray())
            ->name(static::getAllowedPatterns());
    }

    /**
     * @return ArrayIterator<string, ConfigInterface>|null
     */
    public function resolve(): ?Iterator
    {
        $results  = [];
        $iterator = $this->getFinder()->getIterator();

        /**
         * @var SplFileInfo $file
         */
        foreach ($iterator as $file) {
            $parsed = $this->getParser()->parse($file->getFileInfo()->getRealPath());
            if (! $parsed) {
                continue;
            }

            $results[$file->getRealPath()] = $parsed;
        }

        return ! empty($results)
            ? new ArrayIterator($results)
            : null;
    }

    private static function parserException(): RuntimeException
    {
        return new RuntimeException('No parser was set. (use `' . __CLASS__ . '::setParser($parser)`)');
    }
}
