<?php declare(strict_types=1);

namespace HJerichen\ProphecyPHP;

use HJerichen\ProphecyPHP\Exception\FunctionProphecyNotFoundException;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionCallDetector
{
    private static self $instance;

    public static function getInstance(): FunctionCallDetector
    {
        if (!isset(self::$instance)) {
            self::$instance = new self(FunctionProphecyStorage::getInstance());
        }
        return self::$instance;
    }

    public function __construct(
        private readonly FunctionProphecyStorage $functionProphecyStorage
    ) {
    }

    public function functionCalled(string $namespace, string $functionName, array $arguments): mixed
    {
        try {
            $functionProphecy = $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments);
            return $functionProphecy->makeCall();
        } catch (FunctionProphecyNotFoundException $exception) {
            if ($this->functionProphecyStorage->hasFunctionPropheciesForFunctionName($namespace, $functionName)) {
                throw $exception;
            }
            return call_user_func_array("\\$functionName", $arguments);
        }
    }
}