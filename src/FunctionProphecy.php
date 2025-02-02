<?php
declare(strict_types=1);

namespace HJerichen\ProphecyPHP;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 * @noinspection PhpClassCanBeReadonlyInspection
 */
class FunctionProphecy
{
    public function __construct(
        private readonly FunctionDelegation $functionDelegation,
        private readonly ArgumentEvaluator $argumentEvaluator,
        private readonly string $functionName,
        private readonly string $namespace,
        private readonly array $arguments
    ) {
    }

    public function getIdentification(): string
    {
        return md5("$this->namespace::$this->functionName::" . serialize($this->arguments));
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getFunctionName(): string
    {
        return $this->functionName;
    }

    public function scoreArguments(array $arguments): int
    {
        return $this->argumentEvaluator->scoreArguments($arguments) ?: 0;
    }

    public function makeCall(): mixed
    {
        return $this->functionDelegation->delegate($this->functionName, $this->arguments);
    }
}