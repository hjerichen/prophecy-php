<?php
/** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

namespace HJerichen\ProphecyPHP\Tests\Unit;

use HJerichen\ProphecyPHP\FunctionProphecyStorage;
use HJerichen\ProphecyPHP\FunctionRevealer;
use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use SebastianBergmann\Template\Template;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class PHPProphetTest extends TestCase
{
    private PHPProphet $phpProphet;
    private MockObject $prophet;

    protected function setUp(): void
    {
        parent::setUp();

        $this->prophet = $this->createMock(Prophet::class);

        $this->phpProphet = new PHPProphet($this->prophet);
    }


    /* TESTS */

    public function testProphesize(): void
    {
        $namespace = 'namespace';
        $textTemplate = new Template(__DIR__ . '/../../src/function.tpl');
        $functionRevealer = new FunctionRevealer($textTemplate);
        $functionProphecyStorage = FunctionProphecyStorage::getInstance();

        $expected = new NamespaceProphecy(
            $functionProphecyStorage,
            $functionRevealer,
            $this->prophet,
            $namespace,
        );
        $actual = $this->phpProphet->prophesize($namespace);
        self::assertEquals($expected, $actual);
    }

    public function testCheckPredictions(): void
    {
        $this->prophet->expects(self::once())->method('checkPredictions')->with();

        $this->phpProphet->checkPredictions();
    }
}
