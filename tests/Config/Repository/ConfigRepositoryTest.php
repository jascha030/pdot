<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository;

use Jascha030\Dotfiles\Config\ConfigInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use function Jascha030\Dotfiles\container;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

/**
 * @covers \Jascha030\Dotfiles\Config\Repository\ConfigRepository
 *
 * @internal
 */
final class ConfigRepositoryTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetPriority(): void
    {
        assertEquals(
            ConfigRepository::PRIO_NORMAL,
            $this->getRepository()::getPriority()
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testResolve(): void
    {
        assertInstanceOf(
            ConfigInterface::class,
            $this->getRepository()->resolve()
        );
    }

    /**
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface
     */
    private function getRepository(): MockObject|ConfigRepository
    {
        $mock = $this->getMockForAbstractClass(ConfigRepository::class);
        $mock->method('resolve')->willReturn(container()->get('default.config'));

        return $mock;
    }
}
