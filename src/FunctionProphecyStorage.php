<?php


namespace HJerichen\ProphecyPHP;


use HJerichen\ProphecyPHP\Exception\FunctionProphecyNotFoundException;

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
        $this->functionProphecies[$namespace][$functionName][$functionProphecy->getIdentification()] = $functionProphecy;
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
        $functionProphecy = $this->getFunctionProphecyWithHighestScore($namespace, $functionName, $arguments);

        if ($functionProphecy === null) {
            throw new FunctionProphecyNotFoundException($namespace, $functionName, $arguments);
        }

        return $functionProphecy;
    }

    /**
     * @param string $namespace
     * @param string $functionName
     * @param array $arguments
     * @return FunctionProphecy|null
     */
    private function getFunctionProphecyWithHighestScore(string $namespace, string $functionName, array $arguments): ?FunctionProphecy
    {
        $functionProphecies = $this->getFunctionPropheciesForFunctionName($namespace, $functionName);
        if (count($functionProphecies) === 0) {
            return null;
        }

        $functionProphecyWithHighestScore = array_shift($functionProphecies);
        foreach ($functionProphecies as $functionProphecy) {
            if ($functionProphecy->scoreArguments($arguments) > $functionProphecyWithHighestScore->scoreArguments($arguments)) {
                $functionProphecyWithHighestScore = $functionProphecy;
            }
        }

        if ($functionProphecyWithHighestScore->scoreArguments($arguments) <= 0) {
            return null;
        }
        return $functionProphecyWithHighestScore;
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