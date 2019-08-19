<?php

namespace HJerichen\ProphecyPHP\Integration;

include_once 'IntegrationWithOtherClass.php';

use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class IntegrationWithOtherClassTest
 * @package integration
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class IntegrationWithOtherClassTest extends TestCase
{
    use PHPProphetTrait;

    /**
     * @var IntegrationWithOtherClass
     */
    private $object;
    /**
     * @var NamespaceProphecy
     */
    private $php;

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

    /**
     *
     */
    public function testGetMicroTimeWithNotMocked(): void
    {
        $actual = $this->object->getMicroTime();
        $this->assertTrue($actual > 0);
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
        $this->assertEquals($expected, $actual);
    }


    /* HELPERS */
}
