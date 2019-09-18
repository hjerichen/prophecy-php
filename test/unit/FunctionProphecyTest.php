<?php

namespace HJerichen\ProphecyPHP;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class FunctionProphecyTest
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionProphecyTest extends TestCase
{
    /**
     * @var FunctionProphecy
     */
    private $functionProphecy;
    /**
     * @var FunctionDelegation | ObjectProphecy
     */
    private $functionDelegation;
    /**
     * @var ArgumentEvaluator | ObjectProphecy
     */
    private $argumentEvaluator;
    /**
     * @var string
     */
    private $namespace = 'something';
    /**
     * @var string
     */
    private $functionName = 'time';
    /**
     * @var mixed[]
     */
    private $arguments = [true];

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->functionDelegation = $this->prophesize(FunctionDelegation::class);
        $this->argumentEvaluator = $this->prophesize(ArgumentEvaluator::class);

        $this->functionProphecy = new FunctionProphecy($this->functionDelegation->reveal(), $this->argumentEvaluator->reveal(), $this->namespace, $this->functionName,
            $this->arguments);
    }

    /**
     *
     */
    public function testGetIdentification(): void
    {
        $expected = 'aaf13e822dd18dbb1dd9d3bca35c71b5';
        $actual = $this->functionProphecy->getIdentification();
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testGetNamespace(): void
    {
        $expected = $this->namespace;
        $actual = $this->functionProphecy->getNamespace();
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testGetFunctionName(): void
    {
        $expected = $this->functionName;
        $actual = $this->functionProphecy->getFunctionName();
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testScoreArguments(): void
    {
        $arguments = [false];

        $this->argumentEvaluator->scoreArguments($arguments)->willReturn(10);

        $expected = 10;
        $actual = $this->functionProphecy->scoreArguments($arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testScoreArgumentsWithFalse(): void
    {
        $arguments = [];

        $this->argumentEvaluator->scoreArguments($arguments)->willReturn(false);

        $expected = 0;
        $actual = $this->functionProphecy->scoreArguments($arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testMakeCall(): void
    {
        $this->functionDelegation->delegate($this->functionName, $this->arguments)->willReturn('something');

        $expected = 'something';
        $actual = $this->functionProphecy->makeCall();
        $this->assertEquals($expected, $actual);
    }
}
