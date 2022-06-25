<?php

declare(strict_types=1);

namespace Jascha030\Dotfiles\Config\Repository;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsString;
use function PHPUnit\Framework\assertNull;

/**
 * @covers \Jascha030\Dotfiles\Config\Repository\ConfigRepository
 * @covers \Jascha030\Dotfiles\Config\Repository\EnvironmentRepository
 *
 * @internal
 */
class EnvironmentRepositoryTest extends TestCase
{
    private const MAP = [
        'PDOT_DESTINATION'    => 'getDestination',
        'PDOT_DOTFILE_DIRS'   => 'getDotDirs',
        'PDOT_UNDOT_PATTERNS' => 'getUndottedPatterns',
    ];

    private static ?array $testEnv = null;

    public static function setUpBeforeClass(): void
    {
        self::$testEnv = [
            'PDOT_DESTINATION'    => dirname(__FILE__, 3) . '/tests/Fixtures/fs/root',
            'PDOT_DOTFILE_DIRS'   => dirname(__FILE__, 3) . '/tests/Fixtures/fs/root/.dotfiles',
            'PDOT_UNDOT_PATTERNS' => ['undotmepls'],
        ];
    }

    public static function tearDownAfterClass(): void
    {
        self::$testEnv = null;
    }

    public function testGetDescription(): void
    {
        assertIsString($this->getRepository()->getDescription());
    }

    public function testGetName(): void
    {
        assertIsString($this->getRepository()->getName());
    }

    public function testGetPriority(): void
    {
        assertEquals(ConfigRepository::PRIO_HIGH, $this->getRepository()->getPriority());
    }

    public function testResolve(): void
    {
        $_SERVER = array_merge($_SERVER, self::$testEnv);
        $config  = $this->getRepository()->resolve();

        foreach (self::$testEnv as $key => $value) {
            assertEquals($value, $config->{self::MAP[$key]}());
        }
    }

    /**
     * @depends testResolve
     */
    public function testResolveReturnsNullWhenAllVariablesAreUnset(): void
    {
        foreach (self::$testEnv as $key => $value) {
            unset($_SERVER[$key]);
        }

        assertNull($this->getRepository()->resolve());
    }

    private function getRepository(): ConfigRepositoryInterface
    {
        return new EnvironmentRepository();
    }
}
