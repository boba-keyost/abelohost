<?php declare(strict_types=1);
namespace Tests\Config;

use Config\Config;
use Config\ConfigLoader;
use Config\Error;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Config::class)]
class ConfigTest extends TestCase {
    public static function setUpBeforeClass(): void {
        putenv("ABELOHOSTTEST_DB_PASSWORD=123");
    }

    #[Test]
    public function testToArray(): void
    {
        $loader = new ConfigLoader();
        $loader->setPrefix("ABELOHOSTTEST");
        $loader->setEnvironment("test");
        $loader->setRootDirectory(__DIR__);
        $cfg = new Config($loader)->toArray();

        $this->assertEquals(
            [
                'debug' => true,
                'log_level' => 1,
                'db_host' => 'testhost',
                'db_name' => 'testname',
                'db_user' => 'abelohost',
                'db_password' => '123',
                'db_port' => '3306',
            ],
            $cfg,
        );
    }

    #[Test]
    public function testGetException(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage(Error::ERROR_PROPERTY_UNKNOWN);
        $cfg = new Config();
        $cfg->smthing;
    }

    #[Test]
    public function testSetException(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage(Error::ERROR_PROPERTY_SET_RESTRICTED);
        $cfg = new Config();
        $cfg->logLevel = 1;
    }
}
