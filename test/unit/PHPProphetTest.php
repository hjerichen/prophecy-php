<?php

namespace HJerichen\ProphecyPHP;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\Exception\Prediction\PredictionException;
use Prophecy\Prophet;
use Text_Template;

/**
 * Class PHPProphetTest
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <h.jerichen@nordwest.com>
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
        $textTemplate = new Text_Template(__DIR__ . '/../../src/function.tpl');
        $functionProphecyStorage = FunctionProphecyStorage::getInstance();
        $functionRevealer = new FunctionRevealer($textTemplate);

        $expected = new NamespaceProphecy($this->prophet, 'namespace', $functionProphecyStorage, $functionRevealer);
        $actual = $this->phpProphet->prophesize('namespace');
        $this->assertEquals($expected, $actual);
    }

    /**
     * @throws PredictionException
     */
    public function testCheckPredictions(): void
    {
        $this->prophet->expects($this->once())->method('checkPredictions')->with();

        $this->phpProphet->checkPredictions();
    }


    /* HELPERS */
}
