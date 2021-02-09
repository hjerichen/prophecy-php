<?php declare(strict_types=1);

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
        $message = "Unexpected call of \"{$functionName}\" in namespace \"{$namespace}\" with passed arguments:\n{$unexpectedArguments}";

        parent::__construct($message);
    }
}