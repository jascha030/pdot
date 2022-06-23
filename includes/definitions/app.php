<?php

declare(strict_types=1);

use Jascha030\Dotfiles\Application;
use Jascha030\Dotfiles\Console\Command\ConfigCommand;
use Jascha030\Dotfiles\Console\Command\SyncCommand;

return [
    ConfigCommand::class,
    SyncCommand::class,
    Application::class,
];
