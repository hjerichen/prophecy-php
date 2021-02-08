<?php

namespace HJerichen\ProphecyPHP\Tests\Unit\Exception;

use HJerichen\ProphecyPHP\Exception\FunctionProphecyNotFoundException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionProphecyNotFoundExceptionTest extends TestCase
{
    /** @var FunctionProphecyNotFoundException */
    private $exception;

    protected function setUp(): void
    {
        parent::setUp();

        $this->exception = new FunctionProphecyNotFoundException('something', 'time', [4, 'test']);
    }


    /* TESTS */

    public function testClassImplementsCorrectInterface(): void
    {
        self::assertInstanceOf(RuntimeException::class, $this->exception);
    }

    public function testGetMessage(): void
    {
        $expected = "Unexpected call of \"time\" in namespace \"something\" with passed arguments:\n[4, \"test\"]";
        $actual = $this->exception->getMessage();
        self::assertEquals($expected, $actual);
    }
}
