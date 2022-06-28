<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Finder;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder as BaseFinder;
use Symfony\Component\Finder\SplFileInfo;
use Jascha030\Dotfiles\Config\ConfigInterface;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

/**
 * @covers \Jascha030\Dotfiles\Finder\Finder
 *
 * @internal
 */
class FinderTest extends TestCase
{
    public function testConfigFinderFactory(): void
    {
        assertInstanceOf(BaseFinder::class, Finder::configFinder());
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
        assertCount(1, $files);

        /** @var SplFileInfo $configFile */
        $configFile = reset($files);
        $path       = $configFile->getRealPath();
        assertEquals(dirname(__DIR__) . '/Fixtures/fs/root/.pdot.php', $path);

        $config = include $path;
        assertInstanceOf(ConfigInterface::class, $config);

        return $config;
    }

    /**
     * @depends testConfigFinder
     */
    public function testDotfileFinder(ConfigInterface $config): void
    {
        assertInstanceOf(BaseFinder::class, Finder::dotfileFinder($config));
    }
}
