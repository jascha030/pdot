<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

final class UpCommand extends Command
{
    public function setConfigResolver(): static
    {
    }

    public function getName(): ?string
    {
        return 'up';
    }

    public function configure(): void
    {
        $this->addOption('config', 'c', InputArgument::OPTIONAL);
    }
}
