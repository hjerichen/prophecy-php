<?php


namespace HJerichen\ProphecyPHP;


use Prophecy\Argument\ArgumentsWildcard;

/**
 * Class FunctionProphecy
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionProphecy
{
    /**
     * @var FunctionDelegation
     */
    private $functionDelegation;
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
     * @param string $namespace
     * @param string $functionName
     * @param array $arguments
     */
    public function __construct(FunctionDelegation $functionDelegation, string $namespace, string $functionName, array $arguments)
    {
        $this->namespace = $namespace;
        $this->functionName = $functionName;
        $this->arguments = $arguments;
        $this->functionDelegation = $functionDelegation;
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
     * @param mixed[] $arguments
     * @return bool
     */
    public function isForArguments(array $arguments): bool
    {
        $wildcard = new ArgumentsWildcard($this->arguments);
        return $wildcard->scoreArguments($arguments) > 0;
    }

    /**
     * @return mixed
     */
    public function makeCall()
    {
        return $this->functionDelegation->delegate($this->functionName, $this->arguments);
    }
}