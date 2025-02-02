<?php declare(strict_types=1);

namespace HJerichen\ProphecyPHP;

use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ProphecyInterface;
use Prophecy\Prophet;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
readonly class NamespaceProphecy implements ProphecyInterface
{
    use PHPBuiltInFunctions;

    public function __construct(
        private FunctionProphecyStorage $functionProphecyStorage,
        private FunctionRevealer $functionRevealer,
        private Prophet $prophet,
        private string $namespace,
    ) {
    }

    public function __call(string $functionName, array $arguments): MethodProphecy
    {
        $prophecy = $this->prophet->prophesize(FunctionDelegation::class);

        $functionProphecy = new FunctionProphecy(
            $prophecy->reveal(),
            new ArgumentEvaluator($arguments),
            $functionName,
            $this->namespace,
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