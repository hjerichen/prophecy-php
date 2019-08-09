<?php


namespace HJerichen\ProphecyPHP;


use InvalidArgumentException;

/**
 * Class FunctionCaller
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <h.jerichen@nordwest.com>
 */
class FunctionCallDetector
{
    /**
     * @var FunctionCallDetector
     */
    private static $instance;
    /**
     * @var FunctionProphecyStorage
     */
    private $functionProphecyStorage;

    /**
     * @return FunctionCallDetector
     */
    public static function getInstance(): FunctionCallDetector
    {
        if (self::$instance === null) {
            self::$instance = new self(FunctionProphecyStorage::getInstance());
        }
        return self::$instance;
    }

    /**
     * FunctionCallDetector constructor.
     * @param FunctionProphecyStorage $functionProphecyStorage
     */
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
        } catch (InvalidArgumentException $exception) {
            if ($this->functionProphecyStorage->hasFunctionPropheciesForFunctionName($namespace, $functionName)) {
                throw $exception;
            }
            return call_user_func_array("\\{$functionName}", $arguments);
        }
    }
}