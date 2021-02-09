<?php declare(strict_types=1);

namespace HJerichen\ProphecyPHP;

use HJerichen\ProphecyPHP\Exception\FunctionProphecyNotFoundException;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionCallDetector
{
    private static self $instance;
    private FunctionProphecyStorage $functionProphecyStorage;

    public static function getInstance(): FunctionCallDetector
    {
        if (!isset(self::$instance)) {
            self::$instance = new self(FunctionProphecyStorage::getInstance());
        }
        return self::$instance;
    }

    public function __construct(FunctionProphecyStorage $functionProphecyStorage)
    {
        $this->functionProphecyStorage = $functionProphecyStorage;
    }

    /**
     * @param string $namespace
     * @param string $functionName
     * @param array $arguments
     * @return mixed
     */
    public function functionCalled(string $namespace, string $functionName, array $arguments)
    {
        try {
            $functionProphecy = $this->functionProphecyStorage->getFunctionProphecy($namespace, $functionName, $arguments);
            return $functionProphecy->makeCall();
        } catch (FunctionProphecyNotFoundException $exception) {
            if ($this->functionProphecyStorage->hasFunctionPropheciesForFunctionName($namespace, $functionName)) {
                throw $exception;
            }
            return call_user_func_array("\\{$functionName}", $arguments);
        }
    }
}