<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use Jascha030\Dotfiles\Finder\Finder;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNull;

/**
 * @covers \Jascha030\Dotfiles\Config\Repository\File\ConfigFileRepository
 * @covers \Jascha030\Dotfiles\Config\Repository\File\NativeFileRepository
 *
 * @internal
 */
final class NativeFileRepositoryTest extends TestCase
{
    public function testConstruct(): void
    {
        assertInstanceOf(
            ConfigFileRepositoryInterface::class,
            (new NativeFileRepository())
        );
    }

    /**
     * @depends testConstruct
     */
    public function testGetAllowedPatterns(): void
    {
        $iterator = Finder::configFinder()
            ->in(dirname(__FILE__, 4) . '/Fixtures/fs/root')
            ->name(NativeFileRepository::getAllowedPatterns())
            ->getIterator();

        assertCount(1, iterator_to_array($iterator));
    }

    /**
     * @depends testConstruct
     */
    public function testGetStubPath(): void
    {
        assertNull(NativeFileRepository::getStubPath());
    }
}
