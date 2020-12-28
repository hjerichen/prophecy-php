<?php

namespace HJerichen\ProphecyPHP\Exception;

use Prophecy\Util\StringUtil;
use RuntimeException;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionProphecyNotFoundException extends RuntimeException
{
    public function __construct(string $namespace, string $functionName, array $arguments)
    {
        $unexpectedArguments = (new StringUtil())->stringify($arguments);
        parent::__construct("Unexpected call of \"{$functionName}\" in namespace \"{$namespace}\" with passed arguments:\n{$unexpectedArguments}");
    }
}