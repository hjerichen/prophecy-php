<?php declare(strict_types=1);

namespace HJerichen\ProphecyPHP\Tests\Unit;

use HJerichen\ProphecyPHP\ArgumentEvaluator;
use HJerichen\ProphecyPHP\FunctionDelegation;
use HJerichen\ProphecyPHP\FunctionProphecy;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionProphecyTest extends TestCase
{
    use ProphecyTrait;

    private FunctionProphecy $functionProphecy;
    private ObjectProphecy $functionDelegation;
    private ObjectProphecy $argumentEvaluator;
    private string $namespace = 'something';
    private string $functionName = 'time';
    private array $arguments = [true];

    protected function setUp(): void
    {
        parent::setUp();

        $this->functionDelegation = $this->prophesize(FunctionDelegation::class);
        $this->argumentEvaluator = $this->prophesize(ArgumentEvaluator::class);

        $this->functionProphecy = new FunctionProphecy(
            $this->functionDelegation->reveal(),
            $this->argumentEvaluator->reveal(),
            $this->functionName,
            $this->namespace,
            $this->arguments
        );
    }

    public function testGetIdentification(): void
    {
        $expected = 'aaf13e822dd18dbb1dd9d3bca35c71b5';
        $actual = $this->functionProphecy->getIdentification();
        self::assertEquals($expected, $actual);
    }

    public function testGetNamespace(): void
    {
        $expected = $this->namespace;
        $actual = $this->functionProphecy->getNamespace();
        self::assertEquals($expected, $actual);
    }

    public function testGetFunctionName(): void
    {
        $expected = $this->functionName;
        $actual = $this->functionProphecy->getFunctionName();
        self::assertEquals($expected, $actual);
    }

    public function testScoreArguments(): void
    {
        $arguments = [false];

        $this->argumentEvaluator->scoreArguments($arguments)->willReturn(10);

        $expected = 10;
        $actual = $this->functionProphecy->scoreArguments($arguments);
        self::assertEquals($expected, $actual);
    }

    public function testScoreArgumentsWithFalse(): void
    {
        $arguments = [];

        $this->argumentEvaluator->scoreArguments($arguments)->willReturn(false);

        $expected = 0;
        $actual = $this->functionProphecy->scoreArguments($arguments);
        self::assertEquals($expected, $actual);
    }

    public function testMakeCall(): void
    {
        $this->functionDelegation->delegate($this->functionName, $this->arguments)->willReturn('something');

        $expected = 'something';
        $actual = $this->functionProphecy->makeCall();
        self::assertEquals($expected, $actual);
    }
}
