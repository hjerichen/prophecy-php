<?php


namespace HJerichen\ProphecyPHP\Integration;


/**
 * Class IntegrationWithOtherClass
 * @package integration
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class IntegrationWithOtherClass
{
    /**
     * @return int
     */
    public function getMicroTime(): int
    {
        return microtime(true);
    }
}