<?php
/** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

namespace HJerichen\ProphecyPHP\Tests\Unit;

use HJerichen\ProphecyPHP\PHPProphetTrait;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class PHPProphetTraitTest extends TestCase
{
    use PHPProphetTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->prophesizePHP(__NAMESPACE__);
    }

    public function testProphesizePHP(): void
    {
        $php = $this->prophesizePHP(__NAMESPACE__);
        $php->time()->willReturn(123);
        $php->reveal();

        self::assertEquals(123, time());
    }

    public function testDoubleReveal(): void
    {
        $php = $this->prophesizePHP(__NAMESPACE__);
        $php->time()->willReturn(100);
        $php->reveal();
        self::assertEquals(100, time());

        $php->time()->willReturn(200);
        $php->reveal();
        self::assertEquals(200, time());
    }

    public function testVerifyPhpProphecyDoubles(): void
    {
        $php = $this->prophesizePHP(__NAMESPACE__);
        $php->time()->willReturn(123);
        $php->reveal();

        self::assertEquals(123, time());
        
        // Call the protected method directly to ensure coverage
        $this->verifyPhpProphecyDoubles();
    }

    public function testTearDownPhpProphecy(): void
    {
        $php = $this->prophesizePHP(__NAMESPACE__);
        $php->time()->willReturn(456);
        $php->reveal();

        self::assertEquals(456, time());
        
        // Call the protected method directly to ensure coverage
        $this->tearDownPhpProphecy();
    }

    public function testCountPhpProphecyAssertionsWithMultipleCalls(): void
    {
        // Create multiple prophecies to ensure getProphecies() returns entries
        $php = $this->prophesizePHP(__NAMESPACE__);
        $php->time()->willReturn(789);
        $php->date('Y')->willReturn('2023');
        $php->reveal();

        self::assertEquals(789, time());
        self::assertEquals('2023', date('Y'));
        
        // This should trigger countPhpProphecyAssertions through the finally block
        // and should iterate over the prophecies
        $this->verifyPhpProphecyDoubles();
    }

    public function testCountPhpProphecyAssertionsDirectly(): void
    {
        // Use reflection to call the private method directly
        $php = $this->prophesizePHP(__NAMESPACE__);
        $php->time()->willReturn(1000);
        $php->reveal();
        self::assertEquals(1000, time());

        $reflection = new ReflectionClass($this);
        $method = $reflection->getMethod('countPhpProphecyAssertions');
        $method->invoke($this);
    }
}
