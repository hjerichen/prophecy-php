<?php

namespace HJerichen\ProphecyPHP;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophecy\ProphecyInterface;
use Prophecy\Prophet;
use Text_Template;

/**
 * Class NamespaceProphecyTest
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <h.jerichen@nordwest.com>
 */
class NamespaceProphecyTest extends TestCase
{
    /**
     * @var NamespaceProphecy
     */
    private $namespaceProphecy;
    /**
     * @var Prophet | ObjectProphecy
     */
    private $prophet;
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var FunctionProphecyStorage | ObjectProphecy
     */
    private $functionProphecyStorage;
    /**
     * @var Text_Template | ObjectProphecy
     */
    private $textTemplate;
    /**
     * @var ObjectProphecy | MockObject
     */
    private $objectProphecy;
    /**
     * @var FunctionDelegation | ObjectProphecy
     */
    private $functionDelegation;
    /**
     * @var MethodProphecy | ObjectProphecy
     */
    private $methodProphecy;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->functionDelegation = $this->prophesize(FunctionDelegation::class);
        $this->objectProphecy = $this->createMock(ObjectProphecy::class);
        $this->objectProphecy->method('reveal')->willReturn($this->functionDelegation->reveal());
        $this->methodProphecy = $this->prophesize(MethodProphecy::class);

        $this->prophet = $this->createMock(Prophet::class);
        $this->prophet->method('prophesize')->with(FunctionDelegation::class)->willReturn($this->objectProphecy);

        $this->functionProphecyStorage = $this->prophesize(FunctionProphecyStorage::class);
        $this->textTemplate = $this->prophesize(Text_Template::class);
        $this->namespace = 'namespace';

        $GLOBALS['NamespaceProphecyTest::FunctionExists::Active'] = true;

        $this->namespaceProphecy = new NamespaceProphecy($this->prophet, $this->namespace, $this->functionProphecyStorage->reveal(), $this->textTemplate->reveal());
    }

    /**
     * @param string $functionName
     * @param array $arguments
     */
    private function setUpCallTest(string $functionName, array $arguments): void
    {
        $expectedFunctionProphecy = new FunctionProphecy($this->functionDelegation->reveal(), $this->namespace, $functionName, $arguments);

        $this->functionProphecyStorage->add($expectedFunctionProphecy)->shouldBeCalledOnce();
        $this->objectProphecy->method('__call')->with('delegate', [$functionName, $arguments])->willReturn($this->methodProphecy->reveal());
    }

    /**
     * 
     */
    protected function tearDown(): void
    {
        $GLOBALS['NamespaceProphecyTest::FunctionExists::Active'] = false;
    }


    /* TESTS */

    public function testClassImplementsCorrectInterfaces(): void
    {
        $this->assertInstanceOf(ProphecyInterface::class, $this->namespaceProphecy);
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
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testTime(): void
    {
        $this->setUpCallTest('time', []);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->time();
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testDateWithTimestamp(): void
    {
        $this->setUpCallTest('date', ['Y', 1234]);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->date('Y', 1234);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testFilePutContents(): void
    {
        $this->setUpCallTest('file_put_contents', ['filename', 'content']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->file_put_contents('filename', 'content');
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testReveal(): void
    {
        $functionNames = ['time', 'date'];
        $this->functionProphecyStorage->getFunctionNamesOfSetProphecies($this->namespace)->willReturn($functionNames);

        $expectedTemplateData1 = [
            'namespace' => $this->namespace,
            'functionName' => 'time'
        ];
        $expectedTemplateData2 = [
            'namespace' => $this->namespace,
            'functionName' => 'date'
        ];
        $this->textTemplate->setVar($expectedTemplateData1, false)->shouldBeCalledOnce();
        $this->textTemplate->setVar($expectedTemplateData2, false)->shouldBeCalledOnce();
        $this->textTemplate->render()->willReturn('$_SESSION["test1"] = 1;', '$_SESSION["test2"] = 2;');

        $this->namespaceProphecy->reveal();
        $this->assertEquals(1, $_SESSION['test1']);
        $this->assertEquals(2, $_SESSION['test2']);
    }

    /**
     *
     */
    public function testRevealWithFunctionAlreadyRevealed(): void
    {
        $functionNames = ['time', 'count'];
        $this->functionProphecyStorage->getFunctionNamesOfSetProphecies($this->namespace)->willReturn($functionNames);

        $expectedTemplateData1 = [
            'namespace' => $this->namespace,
            'functionName' => 'time'
        ];
        $this->textTemplate->setVar($expectedTemplateData1, false)->shouldBeCalledOnce();
        $this->textTemplate->render()->willReturn('$_SESSION["test3"] = 3;');

        $this->namespaceProphecy->reveal();
        $this->assertEquals(3, $_SESSION['test3']);
    }

    /**
     *
     */
    public function testUnReveal(): void
    {
        $this->functionProphecyStorage->removeFunctionPropheciesForNamespace($this->namespace)->shouldBeCalledOnce();

        $this->namespaceProphecy->unReveal();
    }


    /* HELPERS */
}


/**q
 * Mock for function "function_exists"
 * @param string $functionName
 * @return bool
 */
function function_exists(string $functionName): bool
{
    if ($GLOBALS['NamespaceProphecyTest::FunctionExists::Active'] === false) {
        return \function_exists($functionName);
    }
    
    switch ($functionName) {
        case 'namespace\time':
        case 'namespace\date':
            return false;
        case 'namespace\count':
            return true;
    }

    throw new InvalidArgumentException("FunctionName \"{$functionName}\" not supported in \"function_exists\".");
}
$GLOBALS['NamespaceProphecyTest::FunctionExists::Active'] = false;