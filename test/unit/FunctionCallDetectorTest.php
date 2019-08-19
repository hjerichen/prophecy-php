<?php

namespace HJerichen\ProphecyPHP;

use HJerichen\ProphecyPHP\Exception\FunctionProphecyNotFoundException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class FunctionCallDetectorTest
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionCallDetectorTest extends TestCase
{
    /**
     * @var FunctionCallDetector
     */
    private $functionCallDetector;
    /**
     * @var FunctionProphecyStorage | ObjectProphecy
     */
    private $functionProphecyStorage;
    /**
     * @var FunctionProphecy | ObjectProphecy
     */
    private $functionProphecy;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->functionProphecyStorage = $this->prophesize(FunctionProphecyStorage::class);
        $this->functionProphecy = $this->prophesize(FunctionProphecy::class);

        $this->functionCallDetector = new FunctionCallDetector($this->functionProphecyStorage->reveal());
    }

    /**
     *
     */
    public function testGetInstance(): void
    {
        $expected = FunctionCallDetector::class;
        $actual = FunctionCallDetector::getInstance();
        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * - getInstance called two times
     * -> same Instance will be returned at second time
     */
    public function testGetInstanceReturnsAlwaysSameInstance(): void
    {
        $expected = FunctionCallDetector::getInstance();
        $actual = FunctionCallDetector::getInstance();
        $this->assertSame($expected, $actual);
    }

    /**
     * - function mocked
     * -> given return value will be returned
     */
    public function testFunctionCalled(): void
    {
        $namespace = 'something';
        $functionName = 'time';
        $arguments = [true];

        $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments)->willReturn($this->functionProphecy->reveal());
        $this->functionProphecy->makeCall()->willReturn(1234);

        $expected = 1234;
        $actual = $this->functionCallDetector->functionCalled($namespace, $functionName, $arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     * - function mocked, but with other arguments
     * -> exception will be thrown
     */
    public function testFunctionCalledWithInvalidArgumentInStorage(): void
    {
        $namespace = 'something';
        $functionName = 'time';
        $arguments = [true];
        $exception = $this->prophesize(InvalidArgumentException::class)->reveal();

        $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments)->willThrow($exception);
        $this->functionProphecyStorage->hasFunctionPropheciesForFunctionName($namespace, $functionName)->willReturn(true);

        $this->expectExceptionObject($exception);

        $this->functionCallDetector->functionCalled($namespace, $functionName, $arguments);
    }

    /**
     *
     * - function not mocked at all.
     * -> standard php function will be called
     */
    public function testFunctionCalledWithFunctionNotMockedAtAll(): void
    {
        $namespace = 'something';
        $functionName = 'time';
        $arguments = [];
        $exception = $this->prophesize(FunctionProphecyNotFoundException::class)->reveal();

        $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments)->willThrow($exception);
        $this->functionProphecyStorage->hasFunctionPropheciesForFunctionName($namespace, $functionName)->willReturn(false);

        $expected = time();
        $actual = $this->functionCallDetector->functionCalled($namespace, $functionName, $arguments);
        $this->assertTrue($actual >= $expected);
    }
}
