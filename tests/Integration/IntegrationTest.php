<?php

namespace HJerichen\ProphecyPHP\Tests\Integration;

use HJerichen\ProphecyPHP\Exception\FunctionProphecyNotFoundException;
use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Exception\Prediction\PredictionException;
use ReflectionProperty;

/**
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

        self::assertEquals(2, time());
    }

    /**
     *
     */
    public function testFunctionWillReturnSimpleCalledTwoTimes(): void
    {
        $this->php->time()->willReturn(2);
        $this->php->reveal();

        self::assertEquals(2, time());
        self::assertEquals(2, time());
    }

    /**
     *
     */
    public function testFunctionWillReturnMultiple(): void
    {
        $this->php->time()->willReturn(2, 5);
        $this->php->reveal();

        self::assertEquals(2, time());
        self::assertEquals(5, time());
    }

    /**
     *
     */
    public function testFunctionWillBeStandardAfterUnReveal(): void
    {
        $this->php->time()->willReturn(2);
        $this->php->reveal();

        self::assertEquals(2, time());

        $this->php->unReveal();
        self::assertNotEquals(2, time());
        self::assertIsInt(time());
    }

    /**
     *
     */
    public function testFunctionWillReturnSimpleWithParameter(): void
    {
        $this->php->date('d.m.Y H:i:s')->willReturn('16.07.2019 21:43:00');
        $this->php->reveal();

        self::assertEquals('16.07.2019 21:43:00', date('d.m.Y H:i:s'));
    }

    /**
     *
     */
    public function testFunctionWillReturnSimpleWithProphecyArgument(): void
    {
        $this->php->date(Argument::any())->willReturn('16.07.2019 21:43:00');
        $this->php->reveal();

        self::assertEquals('16.07.2019 21:43:00', date('d.m.Y H:i:s'));
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

        self::assertEquals('16.07.2019 21:43:00', date('d.m.Y H:i:s', 1000));
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
     */
    public function testFunctionShouldBeCalledNotFulfilled(): void
    {
        $this->php->file_put_contents('/tmp/test.txt', 'test')->shouldBeCalled();
        $this->php->reveal();

        $this->resetProphecy();

        $this->expectException(PredictionException::class);
        $this->phpProphet->checkPredictions();
    }

    /**
     *
     */
    public function testOverwriteProphecy(): void
    {
        $this->php->time()->willReturn(2);
        $this->php->time()->willReturn(4);
        $this->php->reveal();

        self::assertEquals(4, time());
    }

    /**
     *
     */
    public function testCalculateCorrectProphecy(): void
    {
        $this->php->date(Argument::any())->willReturn('2020');
        $this->php->date('Y')->willReturn('2019');
        $this->php->reveal();

        self::assertEquals('2020', date('m'));
        self::assertEquals('2019', date('Y'));
    }


    /* HELPER */

    private function resetProphecy(): void
    {
        $reflectionProperty = new ReflectionProperty(TestCase::class, 'prophet');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this, null);
    }
}