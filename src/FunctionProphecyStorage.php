<?php


namespace HJerichen\ProphecyPHP;


use InvalidArgumentException;

/**
 * Class FunctionProphecyStorage
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionProphecyStorage
{
    /**
     * @var FunctionProphecyStorage
     */
    private static $instance;

    /**
     * @var FunctionProphecy[][][]
     */
    private $functionProphecies = [];

    /**
     * @return FunctionProphecyStorage
     */
    public static function getInstance(): FunctionProphecyStorage
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param FunctionProphecy $functionProphecy
     */
    public function add(FunctionProphecy $functionProphecy): void
    {
        $namespace = $functionProphecy->getNamespace();
        $functionName = $functionProphecy->getFunctionName();
        $this->functionProphecies[$namespace][$functionName][] = $functionProphecy;
    }

    /**
     * @param string $namespace
     * @return string[]
     */
    public function getFunctionNamesOfSetProphecies(string $namespace): array
    {
        if (!isset($this->functionProphecies[$namespace])) {
            return [];
        }
        return array_keys($this->functionProphecies[$namespace]);
    }

    /**
     * @param string $namespace
     * @param string $functionName
     * @param mixed[] $arguments
     * @return FunctionProphecy
     */
    public function getFunctionProphecy(string $namespace, string $functionName, array $arguments): FunctionProphecy
    {
        foreach ($this->getFunctionPropheciesForFunctionName($namespace, $functionName) as $functionProphecy) {
            if ($functionProphecy->isForArguments($arguments)) {
                return $functionProphecy;
            }
        }
        throw new InvalidArgumentException("No php function prophecy set for {$functionName} in {$namespace} with passed parameters.");
    }

    /**
     * @param string $namespace
     * @param string $functionName
     * @return FunctionProphecy[]
     */
    private function getFunctionPropheciesForFunctionName(string $namespace, string $functionName): array
    {
        if (!isset($this->functionProphecies[$namespace][$functionName])) {
            return [];
        }

        return $this->functionProphecies[$namespace][$functionName];
    }

    /**
     * @param string $namespace
     */
    public function removeFunctionPropheciesForNamespace(string $namespace): void
    {
        $this->functionProphecies[$namespace] = [];
    }

    /**
     * @param string $namespace
     * @param string $functionName
     * @return bool
     */
    public function hasFunctionPropheciesForFunctionName(string $namespace, string $functionName): bool
    {
        return count($this->getFunctionPropheciesForFunctionName($namespace, $functionName)) > 0;
    }
}