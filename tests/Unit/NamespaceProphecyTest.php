<?php

namespace HJerichen\ProphecyPHP\Tests\Unit;

use HJerichen\ProphecyPHP\ArgumentEvaluator;
use HJerichen\ProphecyPHP\FunctionDelegation;
use HJerichen\ProphecyPHP\FunctionProphecy;
use HJerichen\ProphecyPHP\FunctionProphecyStorage;
use HJerichen\ProphecyPHP\FunctionRevealer;
use HJerichen\ProphecyPHP\NamespaceProphecy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophecy\ProphecyInterface;
use Prophecy\Prophet;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class NamespaceProphecyTest extends TestCase
{
    use ProphecyTrait;

    /** @var NamespaceProphecy */
    private $namespaceProphecy;
    /** @var string */
    private $namespace;
    /** @var FunctionProphecyStorage | ObjectProphecy */
    private $functionProphecyStorage;
    /** @var FunctionRevealer | ObjectProphecy */
    private $functionRevealer;
    /** @var ObjectProphecy | MockObject */
    private $objectProphecy;
    /** @var FunctionDelegation | ObjectProphecy */
    private $functionDelegation;
    /** @var MethodProphecy | ObjectProphecy */
    private $methodProphecy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->functionProphecyStorage = $this->prophesize(FunctionProphecyStorage::class);
        $this->functionDelegation = $this->prophesize(FunctionDelegation::class);
        $this->functionRevealer = $this->prophesize(FunctionRevealer::class);
        $this->objectProphecy = $this->createMock(ObjectProphecy::class);
        $this->methodProphecy = $this->prophesize(MethodProphecy::class);
        $this->namespace = 'namespace';

        $this->objectProphecy->method('reveal')->willReturn($this->functionDelegation->reveal());

        $prophet = $this->createMock(Prophet::class);
        $prophet->method('prophesize')->with(FunctionDelegation::class)->willReturn($this->objectProphecy);

        $this->namespaceProphecy = new NamespaceProphecy($prophet, $this->namespace, $this->functionProphecyStorage->reveal(), $this->functionRevealer->reveal());
    }

    /**
     * @param string $functionName
     * @param array $arguments
     */
    private function setUpCallTest(string $functionName, array $arguments): void
    {
        $expectedFunctionProphecy = new FunctionProphecy($this->functionDelegation->reveal(), new ArgumentEvaluator($arguments), $this->namespace, $functionName, $arguments);

        $this->functionProphecyStorage->add($expectedFunctionProphecy)->shouldBeCalledOnce();
        $this->objectProphecy->method('__call')->with('delegate', [$functionName, $arguments])->willReturn($this->methodProphecy->reveal());
    }


    /* TESTS */

    public function testClassImplementsCorrectInterfaces(): void
    {
        self::assertInstanceOf(ProphecyInterface::class, $this->namespaceProphecy);
    }

    /**
     * @param string $functionName
     * @param array $arguments
     */
    public function testCall(string $functionName = 'something', array $arguments = []): void
    {
        $this->setUpCallTest($functionName, $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->__call($functionName, $arguments);
        self::assertEquals($expected, $actual);
    }

    public function testPrepare(): void
    {
        $this->functionRevealer->revealFunction($this->namespace, 'time')->shouldBeCalledOnce();
        $this->functionRevealer->revealFunction($this->namespace, 'date')->shouldBeCalledOnce();

        $this->namespaceProphecy->prepare('time', 'date');
    }

    public function testReveal(): void
    {
        $functionNames = ['time', 'date'];
        $this->functionProphecyStorage->getFunctionNamesOfSetProphecies($this->namespace)->willReturn($functionNames);

        $this->functionRevealer->revealFunction($this->namespace, 'time')->shouldBeCalledOnce();
        $this->functionRevealer->revealFunction($this->namespace, 'date')->shouldBeCalledOnce();

        $this->namespaceProphecy->reveal();
    }

    public function testUnReveal(): void
    {
        $this->functionProphecyStorage->removeFunctionPropheciesForNamespace($this->namespace)->shouldBeCalledOnce();

        $this->namespaceProphecy->unReveal();
    }
}
