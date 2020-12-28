<?php

namespace HJerichen\ProphecyPHP;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionProphecy
{
    /**
     * @var FunctionDelegation
     */
    private $functionDelegation;
    /**
     * @var ArgumentEvaluator
     */
    private $argumentEvaluator;
    /**
     * @var string
     */
    private $functionName;
    /**
     * @var mixed[]
     */
    private $arguments;
    /**
     * @var string
     */
    private $namespace;

    /**
     * FunctionProphecy constructor.
     * @param FunctionDelegation $functionDelegation
     * @param ArgumentEvaluator $argumentEvaluator
     * @param string $namespace
     * @param string $functionName
     * @param array $arguments
     */
    public function __construct(FunctionDelegation $functionDelegation, ArgumentEvaluator $argumentEvaluator, string $namespace, string $functionName, array $arguments)
    {
        $this->functionDelegation = $functionDelegation;
        $this->argumentEvaluator = $argumentEvaluator;
        $this->namespace = $namespace;
        $this->functionName = $functionName;
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getIdentification(): string
    {
        return md5("{$this->namespace}::{$this->functionName}::" . serialize($this->arguments));
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return $this->functionName;
    }

    /**
     * @param array $arguments
     * @return int
     */
    public function scoreArguments(array $arguments): int
    {
        return $this->argumentEvaluator->scoreArguments($arguments);
    }

    /**
     * @return mixed
     */
    public function makeCall()
    {
        return $this->functionDelegation->delegate($this->functionName, $this->arguments);
    }
}