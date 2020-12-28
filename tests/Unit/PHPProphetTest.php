<?php

namespace HJerichen\ProphecyPHP\Tests\Unit;

use HJerichen\ProphecyPHP\FunctionProphecyStorage;
use HJerichen\ProphecyPHP\FunctionRevealer;
use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\Exception\Prediction\PredictionException;
use Prophecy\Prophet;
use SebastianBergmann\Template\Template;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class PHPProphetTest extends TestCase
{
    /**
     * @var PHPProphet
     */
    private $phpProphet;
    /**
     * @var Prophet | MockObject
     */
    private $prophet;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->prophet = $this->createMock(Prophet::class);

        $this->phpProphet = new PHPProphet($this->prophet);
    }


    /* TESTS */

    /**
     *
     */
    public function testProphesize(): void
    {
        $textTemplate = new Template(__DIR__ . '/../../src/function.tpl');
        $functionProphecyStorage = FunctionProphecyStorage::getInstance();
        $functionRevealer = new FunctionRevealer($textTemplate);

        $expected = new NamespaceProphecy($this->prophet, 'namespace', $functionProphecyStorage, $functionRevealer);
        $actual = $this->phpProphet->prophesize('namespace');
        self::assertEquals($expected, $actual);
    }

    /**
     * @throws PredictionException
     */
    public function testCheckPredictions(): void
    {
        $this->prophet->expects(self::once())->method('checkPredictions')->with();

        $this->phpProphet->checkPredictions();
    }


    /* HELPERS */
}
