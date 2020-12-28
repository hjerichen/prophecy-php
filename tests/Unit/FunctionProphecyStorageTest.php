<?php
/** @noinspection DuplicatedCode */

namespace HJerichen\ProphecyPHP\Tests\Unit;

use HJerichen\ProphecyPHP\Exception\FunctionProphecyNotFoundException;
use HJerichen\ProphecyPHP\FunctionProphecy;
use HJerichen\ProphecyPHP\FunctionProphecyStorage;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionProphecyStorageTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var FunctionProphecyStorage
     */
    private $functionProphecyStorage;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->functionProphecyStorage = new FunctionProphecyStorage();
    }


    /* TESTS */

    /**
     *
     */
    public function testGetInstance(): void
    {
        $expected = FunctionProphecyStorage::class;
        $actual = FunctionProphecyStorage::getInstance();
        self::assertInstanceOf($expected, $actual);
    }

    /**
     * - getInstance called two times
     * -> same Instance will be returned at second time
     */
    public function testGetInstanceReturnsAlwaysSameInstance(): void
    {
        $expected = FunctionProphecyStorage::getInstance();
        $actual = FunctionProphecyStorage::getInstance();
        self::assertSame($expected, $actual);
    }

    /**
     * - no prophecies set
     */
    public function testGetFunctionNamesOfSetProphecies(): void
    {
        $expected = [];
        $actual = $this->functionProphecyStorage->getFunctionNamesOfSetProphecies('namespace');
        self::assertEquals($expected, $actual);
    }

    /**
     * - two set prophecies for namespace
     */
    public function testGetFunctionNamesOfSetPropheciesWithTwoSetForNamespace(): void
    {
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace1', 'time'));
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace1', 'count'));
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace2', 'file_get_contents'));

        $expected = [
            'time',
            'count'
        ];
        $actual = $this->functionProphecyStorage->getFunctionNamesOfSetProphecies('namespace1');
        self::assertEquals($expected, $actual);
    }

    /**
     * - no set prophecies for namespace
     */
    public function testGetFunctionNamesOfSetPropheciesWithNoSetForNamespace(): void
    {
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace2', 'file_get_contents'));

        $expected = [];
        $actual = $this->functionProphecyStorage->getFunctionNamesOfSetProphecies('namespace1');
        self::assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testGetFunctionProphecy(): void
    {
        $namespace = 'namespace';
        $functionName = 'time';
        $arguments = ['test'];
        $functionProphecy = $this->createFunctionProphecy($namespace, $functionName, $arguments);

        $this->functionProphecyStorage->add($functionProphecy);
        $this->functionProphecyStorage->add($this->createFunctionProphecy($namespace, 'count', $arguments));
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace2', 'file_get_contents', $arguments));

        $expected = $functionProphecy;
        $actual = $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments);
        self::assertEquals($expected, $actual);
    }

    /**
     * - another prophecy set for namespace and name but not for arguments
     */
    public function testGetFunctionProphecyWishSetAnother(): void
    {
        $namespace = 'namespace';
        $functionName = 'time';
        $arguments = ['test'];
        $functionProphecy = $this->createFunctionProphecy($namespace, $functionName, $arguments);

        $this->functionProphecyStorage->add($this->createFunctionProphecy($namespace, $functionName, $arguments, 0));
        $this->functionProphecyStorage->add($functionProphecy);
        $this->functionProphecyStorage->add($this->createFunctionProphecy($namespace, 'count', $arguments));
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace2', 'file_get_contents', $arguments));

        $expected = $functionProphecy;
        $actual = $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments);
        self::assertEquals($expected, $actual);
    }

    /**
     * - no matching prophecy set
     */
    public function testGetFunctionProphecyWithNoMatchingSet(): void
    {
        $namespace = 'namespace';
        $functionName = 'time';
        $arguments = [1000, 'test'];

        $this->functionProphecyStorage->add($this->createFunctionProphecy($namespace, 'count', $arguments));
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace2', 'file_get_contents', $arguments));

        $this->expectExceptionObject(new FunctionProphecyNotFoundException($namespace, $functionName, $arguments));

        $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments);
    }

    /**
     *
     */
    public function testGetFunctionProphecyWithScoreCompare(): void
    {
        $namespace = 'namespace';
        $functionName = 'time';
        $arguments = ['test'];
        $functionProphecy1 = $this->createFunctionProphecy($namespace, $functionName, $arguments, 4);
        $functionProphecy2 = $this->createFunctionProphecy($namespace, $functionName, $arguments, 8);
        $functionProphecy3 = $this->createFunctionProphecy($namespace, $functionName, $arguments, 0);

        $this->functionProphecyStorage->add($functionProphecy1);
        $this->functionProphecyStorage->add($functionProphecy2);
        $this->functionProphecyStorage->add($functionProphecy3);

        $expected = $functionProphecy2;
        $actual = $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments);
        self::assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testGetFunctionProphecyWithScoreZero(): void
    {
        $namespace = 'namespace';
        $functionName = 'time';
        $arguments = ['test'];
        $functionProphecy = $this->createFunctionProphecy($namespace, $functionName, $arguments, 0);

        $this->functionProphecyStorage->add($functionProphecy);

        $this->expectException(FunctionProphecyNotFoundException::class);
        
        $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments);
    }

    /**
     *
     */
    public function testAddForAlreadyExitingFunctionProphecy(): void
    {
        $namespace = 'namespace';
        $functionName = 'time';
        $arguments = [1000, 'test'];

        $functionProphecy1 = $this->createFunctionProphecy($namespace, $functionName, $arguments);
        $functionProphecy2 = $this->createFunctionProphecy($namespace, $functionName, $arguments);
        $this->functionProphecyStorage->add($functionProphecy1);
        $this->functionProphecyStorage->add($functionProphecy2);

        $expected = $functionProphecy2;
        $actual = $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments);
        self::assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testRemoveFunctionPropheciesForNamespace(): void
    {
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace1', 'time'));

        $this->functionProphecyStorage->removeFunctionPropheciesForNamespace('namespace1');

        $expected = [];
        $actual = $this->functionProphecyStorage->getFunctionNamesOfSetProphecies('namespace1');
        self::assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testRemoveFunctionPropheciesForNamespaceWithOtherNamespace(): void
    {
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace1', 'time'));

        $this->functionProphecyStorage->removeFunctionPropheciesForNamespace('namespace2');

        $expected = ['time'];
        $actual = $this->functionProphecyStorage->getFunctionNamesOfSetProphecies('namespace1');
        self::assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testHasFunctionPropheciesForFunctionName(): void
    {
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace1', 'time'));

        $expected = true;
        $actual = $this->functionProphecyStorage->hasFunctionPropheciesForFunctionName('namespace1', 'time');
        self::assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testHasFunctionPropheciesForFunctionNameWithNotSet(): void
    {
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace1', 'time'));

        $expected = false;
        $actual = $this->functionProphecyStorage->hasFunctionPropheciesForFunctionName('namespace1', 'count');
        self::assertEquals($expected, $actual);
    }


    /* HELPERS */

    /**
     * @param string $namespace
     * @param string $functionName
     * @param array $arguments
     * @param int $score
     * @return FunctionProphecy
     */
    private function createFunctionProphecy(string $namespace, string $functionName, array $arguments = [], int $score = 10): FunctionProphecy
    {
        $identification = md5("{$namespace}::{$functionName}::{$score}");

        $functionProphecy = $this->prophesize(FunctionProphecy::class);
        $functionProphecy->getIdentification()->willReturn($identification);
        $functionProphecy->getNamespace()->willReturn($namespace);
        $functionProphecy->getFunctionName()->willReturn($functionName);
        $functionProphecy->scoreArguments($arguments)->willReturn($score);
        return $functionProphecy->reveal();
    }
}
