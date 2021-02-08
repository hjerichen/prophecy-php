<?php

namespace HJerichen\ProphecyPHP\Tests\Integration;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class IntegrationWithOtherClass
{
    public function getMicroTime(): int
    {
        return microtime(true);
    }
}