<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Finder;

use Jascha030\Dotfiles\Config\ConfigInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder as BaseFinder;
use Symfony\Component\Finder\SplFileInfo;

use function dirname;

/**
 * @covers \Jascha030\Dotfiles\Finder\Finder
 *
 * @internal
 */
class FinderTest extends TestCase
{
    public function testConfigFinderFactory(): void
    {
        self::assertInstanceOf(BaseFinder::class, Finder::configFinder());
    }

    /**
     * @depends testConfigFinderFactory
     */
    public function testConfigFinder(): ConfigInterface
    {
        $iterator = Finder::configFinder()
            ->in(dirname(__DIR__) . '/Fixtures/fs/root')
            ->name(['.pdot.php'])
            ->getIterator();

        $files = iterator_to_array($iterator);
        self::assertCount(1, $files);

        /** @var SplFileInfo $configFile */
        $configFile = reset($files);
        $path       = $configFile->getRealPath();

        self::assertEquals(dirname(__DIR__) . '/Fixtures/fs/root/.pdot.php', $path);

        $config = include $path;
        self::assertInstanceOf(ConfigInterface::class, $config);

        return $config;
    }

    /**
     * @depends testConfigFinder
     */
    public function testDotfileFinder(ConfigInterface $config): void
    {
        self::assertInstanceOf(BaseFinder::class, Finder::dotfileFinder($config));
    }
}
