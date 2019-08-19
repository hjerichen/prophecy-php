<?php

namespace HJerichen\ProphecyPHP;

use PHPUnit\Framework\TestCase;

/**
 * Class FunctionDelegationTest
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionDelegationTest extends TestCase
{
    /**
     * @var FunctionDelegation
     */
    private $functionDelegation;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->functionDelegation = new FunctionDelegation();
    }


    /* TESTS */

    /**
     *
     */
    public function testDelegate(): void
    {
        $this->expectOutputString('');

        $this->functionDelegation->delegate('time', []);
    }


    /* HELPERS */
}
