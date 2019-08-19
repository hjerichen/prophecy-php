<?php

namespace HJerichen\ProphecyPHP\Integration;

use HJerichen\ProphecyPHP\Exception\FunctionProphecyNotFoundException;
use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Exception\Prediction\PredictionException;
use ReflectionException;
use ReflectionProperty;

/**
 * Class IntegrationTest
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class IntegrationTest extends TestCase
{
    use PHPProphetTrait;

    /**
     * @var NamespaceProphecy
     */
    private $php;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->php = $this->prophesizePHP(__NAMESPACE__);
    }

    /**
     *
     */
    public function testFunctionWillReturnSimple(): void
    {
        $this->php->time()->willReturn(2);
        $this->php->reveal();

        $this->assertEquals(2, time());
    }

    /**
     *
     */
    public function testFunctionWillReturnSimpleCalledTwoTimes(): void
    {
        $this->php->time()->willReturn(2);
        $this->php->reveal();

        $this->assertEquals(2, time());
        $this->assertEquals(2, time());
    }

    /**
     *
     */
    public function testFunctionWillReturnMultiple(): void
    {
        $this->php->time()->willReturn(2, 5);
        $this->php->reveal();

        $this->assertEquals(2, time());
        $this->assertEquals(5, time());
    }

    /**
     *
     */
    public function testFunctionWillBeStandardAfterUnReveal(): void
    {
        $this->php->time()->willReturn(2);
        $this->php->reveal();

        $this->assertEquals(2, time());

        $this->php->unReveal();
        $this->assertNotEquals(2, time());
        $this->assertIsInt(time());
    }

    /**
     *
     */
    public function testFunctionWillReturnSimpleWithParameter(): void
    {
        $this->php->date('d.m.Y H:i:s')->willReturn('16.07.2019 21:43:00');
        $this->php->reveal();

        $this->assertEquals('16.07.2019 21:43:00', date('d.m.Y H:i:s'));
    }

    /**
     *
     */
    public function testFunctionWillReturnSimpleWithProphecyArgument(): void
    {
        $this->php->date(Argument::any())->willReturn('16.07.2019 21:43:00');
        $this->php->reveal();

        $this->assertEquals('16.07.2019 21:43:00', date('d.m.Y H:i:s'));
    }

    /**
     *
     */
    public function testFunctionWillReturnSimpleWithWrongParameter(): void
    {
        $this->php->date('d.m.Y H:i:s')->willReturn('16.07.2019 21:43:00');
        $this->php->reveal();

        $this->expectException(FunctionProphecyNotFoundException::class);

        date('d.m.Y H:i');
    }

    /**
     *
     */
    public function testFunctionWillReturnSimpleWithMoreThenOneParameter(): void
    {
        $this->php->date('d.m.Y H:i:s', 1000)->willReturn('16.07.2019 21:43:00');
        $this->php->reveal();

        $this->assertEquals('16.07.2019 21:43:00', date('d.m.Y H:i:s', 1000));
    }

    /**
     *
     */
    public function testFunctionWillReturnSimpleWithMoreThenOneParameterButWithOnyOneCalled(): void
    {
        $this->php->date('d.m.Y H:i:s', 1000)->willReturn('16.07.2019 21:43:00');
        $this->php->reveal();

        $this->expectException(FunctionProphecyNotFoundException::class);

        date('d.m.Y H:i:s');
    }

    /**
     *
     */
    public function testFunctionShouldBeCalledFulfilled(): void
    {
        $this->php->file_put_contents('/tmp/test.txt', 'test')->shouldBeCalled();
        $this->php->reveal();

        file_put_contents('/tmp/test.txt', 'test');
    }

    /**
     *
     * @throws PredictionException
     * @throws ReflectionException
     */
    public function testFunctionShouldBeCalledNotFulfilled(): void
    {
        $this->php->file_put_contents('/tmp/test.txt', 'test')->shouldBeCalled();
        $this->php->reveal();

        $this->resetProphecy();

        /** @noinspection PhpParamsInspection */
        $this->expectException(PredictionException::class);
        $this->phpProphet->checkPredictions();
    }


    /* HELPER */

    /**
     * @throws ReflectionException
     */
    private function resetProphecy(): void
    {
        $reflectionProperty = new ReflectionProperty(TestCase::class, 'prophet');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this, null);
    }
}