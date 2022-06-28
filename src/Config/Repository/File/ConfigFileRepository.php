<?php

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

abstract class ConfigFileRepository extends ConfigRepository implements ConfigFileRepositoryInterface
{
    private ConfigFileParserInterface $parser;

    public function getFinder(): Finder
    {
        return Finder::configFinder()->name($this->getAllowedPatterns());
    }

    public function setParser(ConfigFileParserInterface $parser): static
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
