<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Exception\Config\Parser;

use InvalidArgumentException;
use SplFileInfo;

class ParserException extends InvalidArgumentException
{
    public const REASON_NOT_FOUND = 0;

    public const REASON_INVALID_CONFIG = 1;

    private const REASON_TYPES = [
        self::REASON_NOT_FOUND      => 'Could not locate file.',
        self::REASON_INVALID_CONFIG => 'Contents could not be parsed as valid config.',
    ];

    private static string $template = 'Error encountered while parsing config file: %s%s.%s Full path: %s';

    public function __construct(string $path, null|string|int $reason = null)
    {
        parent::__construct($this->createMessage(
            $path,
            ! is_string($reason)
                ? $reason
                : self::REASON_TYPES[$reason] ?? null
        ));
    }

    protected function createMessage(string $path, ?string $reason): string
    {
        $info   = new SplFileInfo($path);
        $reason = sprintf('Reason: %s', $reason) ?? '';

        return sprintf(self::$template, $info->getFilename(), $reason, PHP_EOL, $info->getRealPath());
    }
}
