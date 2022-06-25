<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Parser;

use Jascha030\Dotfiles\Config\ConfigInterface;

interface ConfigFileParserInterface
{
    public function parse(string $path): ?ConfigInterface;
}
