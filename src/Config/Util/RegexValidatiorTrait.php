<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Util;

use Symfony\Component\Finder\Glob;

/**
 * @internal
 */
trait RegexValidatiorTrait
{
    private function toRegex(string $str): string
    {
        return $this->isRegex($str) ? $str : Glob::toRegex($str);
    }

    private function isRegex(string $str): bool
    {
        $availableModifiers = 'imsxuADU';

        if (\PHP_VERSION_ID >= 80200) {
            $availableModifiers .= 'n';
        }

        if (preg_match('/^(.{3,}?)[' . $availableModifiers . ']*$/', $str, $m)) {
            $start = substr($m[1], 0, 1);
            $end   = substr($m[1], -1);

            if ($start === $end) {
                return ! preg_match('/[*?[:alnum:] \\\\]/', $start);
            }

            foreach ([['{', '}'], ['(', ')'], ['[', ']'], ['<', '>']] as $delimiters) {
                if ($start === $delimiters[0] && $end === $delimiters[1]) {
                    return true;
                }
            }
        }

        return false;
    }
}
