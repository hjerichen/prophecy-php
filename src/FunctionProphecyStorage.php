<?php declare(strict_types=1);

namespace HJerichen\ProphecyPHP;

use HJerichen\ProphecyPHP\Exception\FunctionProphecyNotFoundException;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionProphecyStorage
{
    private static self $instance;

    /** @var FunctionProphecy[][][] */
    private array $functionProphecies = [];

    public static function getInstance(): FunctionProphecyStorage
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

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

    public function getFunctionProphecy(string $namespace, string $functionName, array $arguments): FunctionProphecy
    {
        $functionProphecy = $this->getFunctionProphecyWithHighestScore($namespace, $functionName, $arguments);

        if ($functionProphecy === null) {
            throw new FunctionProphecyNotFoundException($namespace, $functionName, $arguments);
        }

        return $functionProphecy;
    }

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
        return $this->functionProphecies[$namespace][$functionName] ?? [];
    }

    public function removeFunctionPropheciesForNamespace(string $namespace): void
    {
        $this->functionProphecies[$namespace] = [];
    }

    public function hasFunctionPropheciesForFunctionName(string $namespace, string $functionName): bool
    {
        return count($this->getFunctionPropheciesForFunctionName($namespace, $functionName)) > 0;
    }
}