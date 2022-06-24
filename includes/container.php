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
use function Jascha030\CLI\Helpers\error;

if (! defined('PD_DI_PRODUCTION')) {
    define('PD_DI_PRODUCTION', false);
}

if (! defined('PD_DI_COMPILATION_DIR')) {
    define('PD_DI_COMPILATION_DIR', dirname(__DIR__) . '/.cache/DI');
}

function definitions(): Generator
{
    $dir   = __DIR__ . '/definitions';
    $files = array_diff(scandir($dir), ['..', '.']);

    foreach ($files as $file) {
        if (! str_ends_with($file, '.php')) {
            continue;
        }

        yield str_replace('.php', '', $file) => "{$dir}/{$file}";
    }
}

/**
 * @throws Exception
 */
function bootstrap(bool $production = false): ContainerInterface
{
    $builder = (new ContainerBuilder())
        ->useAnnotations(false)
        ->addDefinitions(...iterator_to_array(definitions()));

    if (true === $production) {
        $proxy_dir = PD_DI_COMPILATION_DIR . '/.cache/DI/proxies';
        $builder->enableCompilation(PD_DI_COMPILATION_DIR)
            ->writeProxiesToFile(true, $proxy_dir);
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

function app(?OutputInterface $output = null): Application
{
    try {
        return container($output)->get(Application::class);
    } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
        error($e->getMessage(), $output);

        exit(1);
    }
}
