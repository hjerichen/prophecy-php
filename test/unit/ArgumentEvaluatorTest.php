<?php

namespace HJerichen\ProphecyPHP;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument\ArgumentsWildcard;

/**
 * Class ArgumentEvaluatorTest
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ArgumentEvaluatorTest extends TestCase
{
    /**
     * @var ArgumentEvaluator
     */
    private $argumentEvaluator;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->argumentEvaluator = new ArgumentEvaluator([]);
    }


    /* TESTS */

    /**
     *
     */
    public function testClassImplementsCorrectInterfaces(): void
    {
        $this->assertInstanceOf(ArgumentsWildcard::class, $this->argumentEvaluator);
    }


    /* HELPERS */
}
