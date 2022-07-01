<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Console\Command;

use Jascha030\Dotfiles\Config\ConfigResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Jascha030\CLI\Helpers\error;

final class UpCommand extends Command
{
    public function __construct(private ConfigResolver $resolver)
    {
        parent::__construct('up');
    }

    public function configure(): void
    {
        $this->addOption('config', 'c', InputArgument::OPTIONAL);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = $this->resolver->getConfig($input->getOption('config'));

        if (null === $config) {
            error('Could not find a config file', $output);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
