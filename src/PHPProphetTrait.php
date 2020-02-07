<?php

namespace HJerichen\ProphecyPHP;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use ReflectionMethod;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
trait PHPProphetTrait
{
    /**
     * @var PHPProphet
     */
    private $phpProphet;

    private function prophesizePHP(string $namespace): NamespaceProphecy
    {
        return $this->getPHPProphet()->prophesize($namespace);
    }

    private function getPHPProphet(): PHPProphet
    {
        if ($this->phpProphet === null) {
            $this->phpProphet = new PHPProphet($this->getProphetFromTestCase());
        }
        return $this->phpProphet;
    }

    private function getProphetFromTestCase(): Prophet
    {
        $refectionMethod = new ReflectionMethod(TestCase::class, 'getProphet');
        $refectionMethod->setAccessible(true);
        return $refectionMethod->invoke($this);
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpUndefinedClassInspection
     * @noinspection PhpUnused
     */
    public function runBare(): void
    {
        try {
            parent::runBare();
        } finally {
            $this->phpProphet->unReveal();
        }
    }
}