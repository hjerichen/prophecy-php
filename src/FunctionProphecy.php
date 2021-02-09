<?php

namespace HJerichen\ProphecyPHP;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionProphecy
{
    private FunctionDelegation $functionDelegation;
    private ArgumentEvaluator $argumentEvaluator;
    private string $functionName;
    private string $namespace;
    /** @var mixed[] */
    private array $arguments;

    public function __construct(
        FunctionDelegation $functionDelegation,
        ArgumentEvaluator $argumentEvaluator,
        string $functionName,
        string $namespace,
        array $arguments
    ) {
        $this->functionDelegation = $functionDelegation;
        $this->argumentEvaluator = $argumentEvaluator;
        $this->functionName = $functionName;
        $this->namespace = $namespace;
        $this->arguments = $arguments;
    }

    public function getIdentification(): string
    {
        return md5("{$this->namespace}::{$this->functionName}::" . serialize($this->arguments));
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
        return $this->argumentEvaluator->scoreArguments($arguments);
    }

    /** @return mixed */
    public function makeCall()
    {
        return $this->functionDelegation->delegate($this->functionName, $this->arguments);
    }
}