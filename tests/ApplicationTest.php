<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application as BaseApplication;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

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

        assertInstanceOf(BaseApplication::class, $app);

        assertEquals(Application::APP_NAME, $app->getName());
        assertEquals(Application::VERSION, $app->getVersion());
    }
}
