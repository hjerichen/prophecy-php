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
     * @return mixed
     * @noinspection PhpReturnDocTypeMismatchInspection
     * @psalm-suppress InvalidReturnType, PossiblyUnusedParam
     */
    public function delegate(string $functionName, array $arguments)
    {
        // only needed for the mock object.
    }
}