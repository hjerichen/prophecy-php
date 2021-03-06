<?php declare(strict_types=1);

namespace HJerichen\ProphecyPHP;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use ReflectionException;
use ReflectionMethod;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
trait PHPProphetTrait
{
    private PHPProphet $phpProphet;

    /**
     * @param string $namespace
     * @return NamespaceProphecy
     * @throws ReflectionException
     */
    private function prophesizePHP(string $namespace): NamespaceProphecy
    {
        return $this->getPHPProphet()->prophesize($namespace);
    }

    /**
     * @return PHPProphet
     * @throws ReflectionException
     */
    private function getPHPProphet(): PHPProphet
    {
        if (!isset($this->phpProphet)) {
            $this->phpProphet = new PHPProphet($this->getProphetFromTestCase());
        }
        return $this->phpProphet;
    }

    /**
     * @return Prophet
     * @throws ReflectionException
     */
    private function getProphetFromTestCase(): Prophet
    {
        $refectionMethod = new ReflectionMethod(TestCase::class, 'getProphet');
        $refectionMethod->setAccessible(true);
        return $refectionMethod->invoke($this);
    }

    /**
     * @noinspection PhpUnused
     * @noinspection UnknownInspectionInspection
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpUndefinedClassInspection
     */
    public function runBare(): void
    {
        try {
            parent::runBare();
        } finally {
            if (isset($this->phpProphet)) {
                $this->phpProphet->unReveal();
            }
        }
    }
}
