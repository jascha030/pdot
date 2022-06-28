<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Parser;

use Jascha030\Dotfiles\Config\ConfigInterface;
use Jascha030\Dotfiles\Exception\Config\Parser\ParserException;

final class NativeFileParser implements ConfigFileParserInterface
{
    public function parse(string $path): ConfigInterface
    {
        if (! file_exists($path)) {
            throw new ParserException($path);
        }

        $contents = include $path;

        if (! is_subclass_of($contents, ConfigInterface::class)) {
            throw new ParserException($path);
        }

        return $contents;
    }
}
