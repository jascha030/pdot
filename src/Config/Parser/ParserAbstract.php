<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Parser;

use Jascha030\Dotfiles\Config\ConfigInterface;
use SplFileInfo;

abstract class ParserAbstract implements ConfigFileParserInterface
{
    abstract public function getFilePattern(): array;

    abstract public function parse(SplFileInfo $fileInfo): ConfigInterface;

    private function getHome(): string
    {
        return getenv('HOME');
    }
}
