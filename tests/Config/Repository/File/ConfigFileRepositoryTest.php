<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository\File;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

/**
 * @covers \Jascha030\Dotfiles\Config\Repository\File\ConfigFileRepository
 *
 * @internal
 */
final class ConfigFileRepositoryTest extends TestCase
{
    public function testSetSearchDirs(): void
    {
        assertEquals(
            $this->getSearchDirs(),
            $this->getRepository()->setSearchDirs($this->getSearchDirs())->getSearchDirs()
        );
    }

    public function testConstruct(): void
    {
        assertInstanceOf(
            ConfigFileRepositoryInterface::class,
            $this->getRepository()
        );
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
        };
    }
}
