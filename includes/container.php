<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles;

use DI\ContainerBuilder;
use Exception;
use Generator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use function Jascha030\CLI\Helpers\error;

if (! defined('PD_DI_PRODUCTION')) {
    define('PD_DI_PRODUCTION', false);
}

if (! defined('PD_DI_COMPILATION_DIR')) {
    define('PD_DI_COMPILATION_DIR', dirname(__DIR__) . '/.cache/DI');
}

/**
 * @internal
 */
function definitions(): Generator
{
    $finder = Finder::create()
        ->in(__DIR__ . '/definitions')
        ->name('*.php');

    yield from collect($finder)
        ->map(static fn (SplFileInfo $f) => $f->getRealPath())
        ->values();
}

/**
 * @throws Exception
 */
function bootstrap(bool $production = false): ContainerInterface
{
    $builder = (new ContainerBuilder())
        ->useAnnotations(false)
        ->addDefinitions(...definitions());

    if (true === $production) {
        $builder
            ->enableCompilation(PD_DI_COMPILATION_DIR)
            ->writeProxiesToFile(true, PD_DI_COMPILATION_DIR . '/.cache/DI/proxies');
    }

    return $builder->build();
}

function container(?OutputInterface $output = null): ContainerInterface
{
    static $container;

    try {
        return $container ?? bootstrap(PD_DI_PRODUCTION);
    } catch (Exception $e) {
        error($e->getMessage(), $output);

        exit(1);
    }
}

function app(?OutputInterface $output = null): int|Application
{
    try {
        return container($output)->get(Application::class);
    } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
        error($e->getMessage(), $output);

        exit(1);
    }
}
