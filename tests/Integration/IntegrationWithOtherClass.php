<?php declare(strict_types=1);

namespace HJerichen\ProphecyPHP\Tests\Integration;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class IntegrationWithOtherClass
{
    public function getMicroTime(): int
    {
        return (int)microtime(true);
    }
}