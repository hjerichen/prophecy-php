<?php

namespace HJerichen\ProphecyPHP;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionProphecy
{
    /** @var FunctionDelegation */
    private $functionDelegation;
    /** @var ArgumentEvaluator */
    private $argumentEvaluator;
    /** @var string */
    private $functionName;
    /** @var mixed[] */
    private $arguments;
    /** @var string */
    private $namespace;

    public function __construct(FunctionDelegation $functionDelegation, ArgumentEvaluator $argumentEvaluator, string $namespace, string $functionName, array $arguments)
    {
        $this->functionDelegation = $functionDelegation;
        $this->argumentEvaluator = $argumentEvaluator;
        $this->namespace = $namespace;
        $this->functionName = $functionName;
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