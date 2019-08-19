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

        $this->exception = new FunctionProphecyNotFoundException();
    }


    /* TESTS */

    /**
     *
     */
    public function testClassImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(RuntimeException::class, $this->exception);
    }


    /* HELPERS */
}
