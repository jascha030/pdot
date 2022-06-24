<?php

declare(strict_types=1);

use Jascha030\Dotfiles\Application;
use Jascha030\Dotfiles\Console\Command\ConfigCommand;
use Jascha030\Dotfiles\Console\Command\SyncCommand;
use Symfony\Component\Console\Application as BaseApplication;
use function DI\autowire;

/**
 * Console application definitions.
 *
 * @see https://php-di.org/doc/php-definitions.html
 */
return [
    ConfigCommand::class   => autowire(),
    SyncCommand::class     => autowire(),
    BaseApplication::class => autowire(Application::class),
];
