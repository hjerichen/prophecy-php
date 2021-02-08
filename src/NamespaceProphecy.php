<?php

namespace HJerichen\ProphecyPHP;

use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ProphecyInterface;
use Prophecy\Prophet;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class NamespaceProphecy implements ProphecyInterface
{
    use PHPBuiltInFunctions;

    /** @var Prophet */
    private $prophet;
    /** @var string */
    private $namespace;
    /** @var FunctionProphecyStorage */
    private $functionProphecyStorage;
    /** @var FunctionRevealer */
    private $functionRevealer;


    public function __construct(
        Prophet $prophet,
        string $namespace,
        FunctionProphecyStorage $functionProphecyStorage,
        FunctionRevealer $functionRevealer
    ) {
        $this->prophet = $prophet;
        $this->namespace = $namespace;
        $this->functionProphecyStorage = $functionProphecyStorage;
        $this->functionRevealer = $functionRevealer;
    }

    public function __call(string $functionName, array $arguments): MethodProphecy
    {
        $prophecy = $this->prophet->prophesize(FunctionDelegation::class);

        $functionProphecy = new FunctionProphecy(
            $prophecy->reveal(),
            new ArgumentEvaluator($arguments),
            $this->namespace,
            $functionName,
            $arguments
        );
        $this->functionProphecyStorage->add($functionProphecy);

        return $prophecy->__call('delegate', [$functionName, $arguments]);
    }

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
        $functionNames = $this->functionProphecyStorage->getFunctionNamesOfSetProphecies($this->namespace);
        foreach ($functionNames as $functionName) {
            $this->functionRevealer->revealFunction($this->namespace, $functionName);
        }
    }

    public function unReveal(): void
    {
        $this->functionProphecyStorage->removeFunctionPropheciesForNamespace($this->namespace);
    }
}