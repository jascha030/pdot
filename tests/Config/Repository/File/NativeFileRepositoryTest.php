<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use ArrayIterator;
use Jascha030\Dotfiles\Config\ConfigInterface;
use Jascha030\Dotfiles\Config\Parser\NativeFileParser;
use Jascha030\Dotfiles\Finder\Finder;
use PHPUnit\Framework\TestCase;

use function dirname;

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
        self::assertInstanceOf(
            ConfigFileRepositoryInterface::class,
            $this->getRepository()
        );
    }

    /**
     * @depends testConstruct
     */
    public function testResolve(): void
    {
        $iterator = $this->getRepository()
            ->setSearchDirs([dirname(__FILE__, 4) . '/Fixtures/fs/root'])
            ->setParser(new NativeFileParser())
            ->resolve();

        self::assertInstanceOf(ArrayIterator::class, $iterator);
        self::assertInstanceOf(ConfigInterface::class, $iterator->current());
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

        self::assertCount(1, iterator_to_array($iterator));
    }

    /**
     * @depends testConstruct
     */
    public function testGetStubPath(): void
    {
        self::assertNull(NativeFileRepository::getStubPath());
    }

    private function getRepository(): NativeFileRepository
    {
        return (new NativeFileRepository())
            ->setSearchDirs([dirname(__FILE__, 4) . '/Fixtures/fs/root']);
    }
}
