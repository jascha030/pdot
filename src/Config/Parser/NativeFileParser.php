<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Parser;

use Jascha030\Dotfiles\Config\ConfigInterface;
use Jascha030\Dotfiles\Exception\Config\Parser\ParserException;
use TypeError;

final class NativeFileParser implements ConfigFileParserInterface
{
    public function parse(string $path): ConfigInterface
    {
        if (!file_exists($path)) {
            throw new ParserException($path);
        }

        try {
            return (static fn (): ConfigInterface => include $path)();
        } catch (TypeError) {
            throw new ParserException($path, ParserException::REASON_INVALID_CONFIG);
        }
    }
}
