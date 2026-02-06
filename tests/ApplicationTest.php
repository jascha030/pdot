<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * @covers \Jascha030\Dotfiles\Application
 *
 * @internal
 */
final class ApplicationTest extends TestCase
{
    public function testConstruct(): void
    {
        $app = new Application();

        self::assertInstanceOf(BaseApplication::class, $app);

        self::assertEquals(Application::APP_NAME, $app->getName());
        self::assertEquals(Application::VERSION, $app->getVersion());
    }

    /**
     * @depends testConstruct
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface
     */
    public function testContainerDefinition(): void
    {
        $app = container()->get(BaseApplication::class);

        self::assertInstanceOf(Application::class, $app);
    }
}
