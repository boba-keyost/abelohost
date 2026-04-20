<?php declare(strict_types=1);
namespace Tests\Config;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use \Config\Config;

#[CoversClass(Config::class)]
class ConfigTest extends TestCase {
    public static function setUpBeforeClass(): void {
        putenv("ABELOHOSTTEST_DB_PASSWORD=123");
    }

    #[Test]
    public function testToArray(): void
    {
        Config::setPrefix("ABELOHOSTTEST");
        Config::setEnvironment("test");
        Config::setRootDirectory(__DIR__);

        $cfg = Config::getInstance()->toArray();

        $this->assertEquals(
            [
                'log_level' => 1,
                'db_host' => 'testhost',
                'db_name' => 'testname',
                'db_user' => 'abelohost',
                'db_password' => '123',
            ],
            $cfg,
        );
    }
}
