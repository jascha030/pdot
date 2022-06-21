<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository;

use Jascha030\Dotfiles\Config\ConfigInterface;

interface ConfigFileParserInterface
{
    public function getFilePattern(): string;

    public function parse(\SplFileInfo $fileInfo): ConfigInterface;
}
