<?php

declare(strict_types=1);

use Jascha030\Dotfiles\Config\Config;
use Psr\Container\ContainerInterface;

return [
    'default.home'   => $_SERVER['HOME'],
    'default.dotdir' => sprintf('%s/.dotfiles', $_SERVER['HOME']),
    'default.config' => static fn (ContainerInterface $container): Config => Config::create()
        ->setDestination($container->get('default.home'))
        ->setDotDirs($container->get('default.dotdir'))
        ->setOrigin('Default config.'),
];
