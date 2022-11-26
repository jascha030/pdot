<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use Illuminate\Support\Collection;
use Jascha030\Dotfiles\Config\Parser\NativeFileParser;
use Jascha030\Dotfiles\Finder\Finder;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNull;

/**
 * @covers \Jascha030\Dotfiles\Config\Repository\File\ConfigFileRepository
 *
 * @internal
 */
final class ConfigFileRepositoryTest extends TestCase
{
    public function testGetFinder(): void
    {
        assertInstanceOf(
            Finder::class,
            $this->getRepository()->getFinder()
        );
    }

    public function testGetStubPath(): void
    {
        assertNull($this->getRepository()::getStubPath());
    }

    public function testGetAllowedPatterns(): void
    {
        assertEquals(
            NativeFileRepository::getAllowedPatterns(),
            $this->getRepository()::getAllowedPatterns()
        );
    }

    public function testParser(): void
    {
        assertEquals(
            $expected = new NativeFileParser(),
            $this->getRepository()->setParser($expected)->getParser()
        );
    }

    public function testSetSearchDirs(): void
    {
        assertEquals(
            $this->getSearchDirs(),
            $this->getRepository()->setSearchDirs($this->getSearchDirs())->getSearchDirs()
        );
    }

    public function testConstruct(): void
    {
        assertInstanceOf(ConfigFileRepositoryInterface::class, $this->getRepository());
    }

    private function getSearchDirs(): array
    {
        return [dirname(__FILE__, 4) . '/Fixtures/fs/root'];
    }

    private function getRepository(): ConfigFileRepository
    {
        return new class () extends ConfigFileRepository {
            public static function getAllowedPatterns(): array|string
            {
                return NativeFileRepository::getAllowedPatterns();
            }

            public static function getName(): string
            {
                return 'mock';
            }

            public static function getDescription(): string
            {
                return 'Private class mock';
            }

            public function getSearchDirs(): ?iterable
            {
                return new Collection([dirname(__FILE__, 4) . '/Fixtures/fs/root']);
            }
        };
    }
}
