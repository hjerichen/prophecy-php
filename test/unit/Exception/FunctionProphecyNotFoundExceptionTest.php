<?php

namespace HJerichen\ProphecyPHP\Exception;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class FunctionProphecyNotFoundExceptionTest
 * @package HJerichen\ProphecyPHP\Exception
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionProphecyNotFoundExceptionTest extends TestCase
{
    /**
     * @var FunctionProphecyNotFoundException
     */
    private $exception;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->exception = new FunctionProphecyNotFoundException('something', 'time', [4, 'test']);
    }


    /* TESTS */

    /**
     *
     */
    public function testClassImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(RuntimeException::class, $this->exception);
    }

    /**
     *
     */
    public function testGetMessage(): void
    {
        $expected = "Unexpected call of \"time\" in namespace \"something\" with passed arguments:\n[4, \"test\"]";
        $actual = $this->exception->getMessage();
        $this->assertEquals($expected, $actual);
    }


    /* HELPERS */
}
