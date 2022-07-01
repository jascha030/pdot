<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

use Generator;
use Iterator;
use IteratorAggregate;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * @internal
 */
final class RawConfigIterator implements IteratorAggregate
{
    private RecursiveIteratorIterator $iterator;

    public function __construct(Iterator|array $prioritised)
    {
        $this->iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($prioritised));
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): Generator
    {
        /** @var ConfigInterface $config */
        foreach ($this->iterator as $config) {
            yield $config->getRaw();
        }
    }
}
