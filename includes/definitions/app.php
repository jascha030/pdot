<?php

declare(strict_types=1);

use Jascha030\Dotfiles\Application;
use Jascha030\Dotfiles\Config\ConfigResolver;
use Jascha030\Dotfiles\Console\Command\ConfigCommand;
use Jascha030\Dotfiles\Console\Command\UpCommand;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application as BaseApplication;
use function DI\autowire;

/**
 * Console application definitions.
 *
 * @see https://php-di.org/doc/php-definitions.html
 */
return [
    ConfigResolver::class => static function (ContainerInterface $container): ConfigResolver {
        return new ConfigResolver(iterator_to_array(
            (static function (array $repositories) use ($container) {
                foreach ($repositories as $class) {
                    yield $container->get($class);
                }
            })($container->get('repositories'))
        ));
    },
    ConfigCommand::class   => autowire(),
    UpCommand::class       => autowire(),
    BaseApplication::class => autowire(Application::class),
];
