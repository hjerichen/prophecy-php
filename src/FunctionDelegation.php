<?php
/** @noinspection PhpInconsistentReturnPointsInspection */
declare(strict_types=1);

namespace HJerichen\ProphecyPHP;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionDelegation
{
    /**
     *
     * @param string $functionName
     * @param mixed[] $arguments
     * @return mixed
     */
    public function delegate(string $functionName, array $arguments)
    {
        // only needed for the mock object.
    }
}