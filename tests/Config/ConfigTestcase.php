<?php
/** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

use PHPUnit\Framework\TestCase;
use function Jascha030\Dotfiles\home;

abstract class ConfigTestcase extends TestCase
{
    public function getExpectedValues(): array
    {
        return [
            'getOrigin'           => 'Created by PHPUnit.',
            'preferMerge'         => true,
            'getDotDirs'          => home() . '/.dotfiles',
            'getDestination'      => home(),
            'getAddDots'          => true,
            'getUndottedPatterns' => null,
            'getIgnoredPatterns'  => '.gitconfig',
        ];
    }
}
