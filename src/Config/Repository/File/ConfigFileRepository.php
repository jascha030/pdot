<?php

/**
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use ArrayIterator;
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

    private array $searchDirs;

    public function __construct()
    {
        $this->searchDirs = [home(), defaultConfigPath()];
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
     * {@inheritDoc}
     *
     * @throws RuntimeException
     */
    public function getParser(): ConfigFileParserInterface
    {
        return $this->parser ?? throw self::parserException();
    }

    public function setSearchDirs(array $directories): static
    {
        $this->searchDirs = $directories;

        return $this;
    }

    public function getSearchDirs(): ?array
    {
        return $this->searchDirs;
    }

    /**
     * {@inheritDoc}
     */
    public function getFinder(): Finder
    {
        return Finder::configFinder()
            ->in($this->getSearchDirs())
            ->name(static::getAllowedPatterns());
    }

    /**
     * {@inheritDoc}
     */
    public function isMatch(string $filePath): bool
    {
        $patterns = static::getAllowedPatterns();

        if (is_string($patterns)) {
            return false !== preg_match($patterns, $filePath);
        }

        foreach ($patterns as $pattern) {
            if (false !== preg_match($pattern, $filePath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @return null|ArrayIterator<string, ConfigInterface>
     */
    public function resolve(): null|Iterator
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
