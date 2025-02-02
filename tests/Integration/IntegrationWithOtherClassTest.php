<?php
declare(strict_types=1);

namespace HJerichen\ProphecyPHP\Tests\Integration;

use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class IntegrationWithOtherClassTest extends TestCase
{
    use PHPProphetTrait;

    private IntegrationWithOtherClass $object;
    private NamespaceProphecy $php;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->php = $this->prophesizePHP(__NAMESPACE__);
        $this->php->prepare('microtime');

        $this->object = new IntegrationWithOtherClass();
    }


    /* TESTS */

    public function testGetMicroTimeWithNotMocked(): void
    {
        $actual = $this->object->getMicroTime();
        self::assertTrue($actual > 0);
    }

    /**
     * Test workaround for php bug 64346 (https://bugs.php.net/bug.php?id=64346)
     */
    public function testGetMicroTimeWithMocked(): void
    {
        $this->object->getMicroTime();

        $this->php->microtime(true)->willReturn(123);
        $this->php->reveal();

        $expected = 123;
        $actual = $this->object->getMicroTime();
        self::assertEquals($expected, $actual);
    }
}
