<?php

namespace HJerichen\ProphecyPHP;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class FunctionProphecyStorageTest
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <h.jerichen@nordwest.com>
 */
class FunctionProphecyStorageTest extends TestCase
{
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
        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * - getInstance called two times
     * -> same Instance will be returned at second time
     */
    public function testGetInstanceReturnsAlwaysSameInstance(): void
    {
        $expected = FunctionProphecyStorage::getInstance();
        $actual = FunctionProphecyStorage::getInstance();
        $this->assertSame($expected, $actual);
    }

    /**
     * - no prophecies set
     */
    public function testGetFunctionNamesOfSetProphecies(): void
    {
        $expected = [];
        $actual = $this->functionProphecyStorage->getFunctionNamesOfSetProphecies('namespace');
        $this->assertEquals($expected, $actual);
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
        $this->assertEquals($expected, $actual);
    }

    /**
     * - no set prophecies for namespace
     */
    public function testGetFunctionNamesOfSetPropheciesWithNoSetForNamespace(): void
    {
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace2', 'file_get_contents'));

        $expected = [];
        $actual = $this->functionProphecyStorage->getFunctionNamesOfSetProphecies('namespace1');
        $this->assertEquals($expected, $actual);
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
        $this->assertEquals($expected, $actual);
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

        $this->functionProphecyStorage->add($this->createFunctionProphecy($namespace, $functionName, $arguments, false));
        $this->functionProphecyStorage->add($functionProphecy);
        $this->functionProphecyStorage->add($this->createFunctionProphecy($namespace, 'count', $arguments));
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace2', 'file_get_contents', $arguments));

        $expected = $functionProphecy;
        $actual = $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     * - no matching prophecy set
     */
    public function testGetFunctionProphecyWithNoMatchingSet(): void
    {
        $namespace = 'namespace';
        $functionName = 'time';
        $arguments = ['test'];

        $this->functionProphecyStorage->add($this->createFunctionProphecy($namespace, 'count', $arguments));
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace2', 'file_get_contents', $arguments));

        $this->expectExceptionObject(new InvalidArgumentException('No php function prophecy set for time in namespace with passed parameters.'));

        $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments);
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
        $this->assertEquals($expected, $actual);
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
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testHasFunctionPropheciesForFunctionName(): void
    {
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace1', 'time'));

        $expected = true;
        $actual = $this->functionProphecyStorage->hasFunctionPropheciesForFunctionName('namespace1', 'time');
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testHasFunctionPropheciesForFunctionNameWithNotSet(): void
    {
        $this->functionProphecyStorage->add($this->createFunctionProphecy('namespace1', 'time'));

        $expected = false;
        $actual = $this->functionProphecyStorage->hasFunctionPropheciesForFunctionName('namespace1', 'count');
        $this->assertEquals($expected, $actual);
    }


    /* HELPERS */

    /**
     * @param string $namespace
     * @param string $functionName
     * @param array $arguments
     * @param bool $isForArguments
     * @return FunctionProphecy
     */
    private function createFunctionProphecy(string $namespace, string $functionName, array $arguments = [], bool $isForArguments = true): FunctionProphecy
    {
        $functionProphecy = $this->prophesize(FunctionProphecy::class);
        $functionProphecy->getNamespace()->willReturn($namespace);
        $functionProphecy->getFunctionName()->willReturn($functionName);
        $functionProphecy->isForArguments($arguments)->willReturn($isForArguments);
        return $functionProphecy->reveal();
    }
}
