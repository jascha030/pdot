<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config;

use Jascha030\Dotfiles\Config\Repository\File\NativeFileRepository;
use PHPUnit\Framework\TestCase;
use function dirname;
use function Jascha030\Dotfiles\container;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNull;

/**
 * @covers \Jascha030\Dotfiles\Config\ConfigResolver
 *
 * @internal
 */
class ConfigResolverTest extends TestCase
{
    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testAddRepository(): void
    {
        assertInstanceOf(ConfigResolver::class, $this->getResolver()->addRepository(NativeFileRepository::class));
    }

    public function testFoundMultiple(): void
    {
    }

    public function testGetByPath(): void
    {
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testGetConfig(): void
    {
        assertNull($this->getResolver()->getConfig());
        assertInstanceOf(
            ConfigInterface::class,
            $this->getResolver()->getConfig(dirname(__DIR__) . '/Fixtures/fs/root/.pdot.php')
        );
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testResolveConfiguration(): void
    {
        assertInstanceOf(ConfigResolver::class, $this->getResolver()->resolveConfiguration());
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testConstruct(): void
    {
        assertInstanceOf(ConfigResolver::class, $this->getResolver());
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getResolver(): ConfigResolver
    {
        return container()->get(ConfigResolver::class);
    }
}
