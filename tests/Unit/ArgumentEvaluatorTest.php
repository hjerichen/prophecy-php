<?php declare(strict_types=1);

namespace HJerichen\ProphecyPHP\Tests\Unit;

use HJerichen\ProphecyPHP\ArgumentEvaluator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument\ArgumentsWildcard;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ArgumentEvaluatorTest extends TestCase
{
    private ArgumentEvaluator $argumentEvaluator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->argumentEvaluator = new ArgumentEvaluator([]);
    }


    /* TESTS */

    public function testClassImplementsCorrectInterfaces(): void
    {
        self::assertInstanceOf(ArgumentsWildcard::class, $this->argumentEvaluator);
    }
}
