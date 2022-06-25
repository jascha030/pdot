<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use ArrayIterator;
use Iterator;
use Jascha030\Dotfiles\Config\ConfigInterface;
use Jascha030\Dotfiles\Config\Repository\ConfigRepository;
use Symfony\Component\Finder\SplFileInfo;

abstract class ConfigFileRepository extends ConfigRepository implements ConfigFileRepositoryInterface
{
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
}
