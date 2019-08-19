<?php


namespace HJerichen\ProphecyPHP;


use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ProphecyInterface;
use Prophecy\Prophet;

/**
 * Class NamespaceProphecy
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class NamespaceProphecy implements ProphecyInterface
{
    use PHPBuiltInFunctions;

    /**
     * @var Prophet
     */
    private $prophet;
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var FunctionProphecyStorage
     */
    private $functionProphecyStorage;
    /**
     * @var FunctionRevealer
     */
    private $functionRevealer;


    /**
     * NamespaceProphecy constructor.
     * @param Prophet $prophet
     * @param string $namespace
     * @param FunctionProphecyStorage $functionProphecyStorage
     * @param FunctionRevealer $functionRevealer
     */
    public function __construct(Prophet $prophet, string $namespace, FunctionProphecyStorage $functionProphecyStorage, FunctionRevealer $functionRevealer)
    {
        $this->prophet = $prophet;
        $this->namespace = $namespace;
        $this->functionProphecyStorage = $functionProphecyStorage;
        $this->functionRevealer = $functionRevealer;
    }

    /**
     * @param string $functionName
     * @param array $arguments
     * @return MethodProphecy
     */
    public function __call(string $functionName, array $arguments): MethodProphecy
    {
        $prophecy = $this->prophet->prophesize(FunctionDelegation::class);

        /** @noinspection PhpParamsInspection */
        $functionProphecy = new FunctionProphecy($prophecy->reveal(), $this->namespace, $functionName, $arguments);
        $this->functionProphecyStorage->add($functionProphecy);

        return $prophecy->__call('delegate', [$functionName, $arguments]);
    }

    /**
     * @param string ...$functionNames
     */
    public function prepare(string ...$functionNames): void
    {
        foreach ($functionNames as $functionName) {
            $this->functionRevealer->revealFunction($this->namespace, $functionName);
        }
    }

    /**
     * Reveals prophecy object (double) .
     */
    public function reveal(): void
    {
        foreach ($this->functionProphecyStorage->getFunctionNamesOfSetProphecies($this->namespace) as $functionName) {
            $this->functionRevealer->revealFunction($this->namespace, $functionName);
        }
    }

    /**
     *
     */
    public function unReveal(): void
    {
        $this->functionProphecyStorage->removeFunctionPropheciesForNamespace($this->namespace);
    }
}