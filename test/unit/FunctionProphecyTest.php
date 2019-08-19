<?php

namespace HJerichen\ProphecyPHP;

use PHPUnit\Framework\TestCase;
use PHPUnit\Util\TestDox\NamePrettifier;
use Prophecy\Argument;
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

        $this->functionProphecy = new FunctionProphecy($this->functionDelegation->reveal(), $this->namespace, $this->functionName, $this->arguments);
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
    public function testIsForArgumentsSimpleTrue(): void
    {
        $expected = true;
        $actual = $this->functionProphecy->isForArguments($this->arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testIsForArgumentsSimpleFalse(): void
    {
        $expected = false;
        $actual = $this->functionProphecy->isForArguments([]);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testIsForArgumentsForEqualObject(): void
    {
        $arguments1 = [
            new NamePrettifier(true)
        ];
        $arguments2 = [
            new NamePrettifier(true)
        ];

        $this->functionProphecy = new FunctionProphecy($this->functionDelegation->reveal(), $this->namespace,
            $this->functionName, $arguments1);

        $expected = true;
        $actual = $this->functionProphecy->isForArguments($arguments2);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testIsForArgumentsForEqualObjectButSameNeeded(): void
    {
        $arguments1 = [
            Argument::is(new NamePrettifier(true))
        ];
        $arguments2 = [
            new NamePrettifier(true)
        ];

        $this->functionProphecy = new FunctionProphecy($this->functionDelegation->reveal(), $this->namespace,
            $this->functionName, $arguments1);

        $expected = false;
        $actual = $this->functionProphecy->isForArguments($arguments2);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testIsForArgumentsForSameObjectAndSameNeeded(): void
    {
        $object = new NamePrettifier(true);
        $arguments1 = [
            Argument::is($object)
        ];
        $arguments2 = [
            $object
        ];

        $this->functionProphecy = new FunctionProphecy($this->functionDelegation->reveal(), $this->namespace,
            $this->functionName, $arguments1);

        $expected = true;
        $actual = $this->functionProphecy->isForArguments($arguments2);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testIsForArgumentsForNotEqualObject(): void
    {
        $arguments1 = [
            new NamePrettifier(true)
        ];
        $arguments2 = [
            new NamePrettifier(false)
        ];

        $this->functionProphecy = new FunctionProphecy($this->functionDelegation->reveal(), $this->namespace, $this->functionName, $arguments1);

        $expected = false;
        $actual = $this->functionProphecy->isForArguments($arguments2);
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
