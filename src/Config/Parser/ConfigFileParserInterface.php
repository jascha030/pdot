<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Parser;

use Jascha030\Dotfiles\Config\ConfigInterface;
use SplFileInfo;

interface ConfigFileParserInterface
{
    public function getFilePattern(): array;

    public function parse(SplFileInfo $fileInfo): ConfigInterface;
}
