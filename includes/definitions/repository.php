<?php

declare(strict_types=1);

use Jascha030\Dotfiles\Config\Parser\NativeFileParser;
use Jascha030\Dotfiles\Config\Repository\File\NativeFileRepository;

use function DI\autowire;
use function DI\create;
use function DI\get;
use function DI\value;

return [
    NativeFileParser::class     => autowire(),
    NativeFileRepository::class => create()->method(
        'setParser',
        get(NativeFileParser::class)
    ),
    'repositories' => value([NativeFileRepository::class]),
];
