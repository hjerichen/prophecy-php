<?php
/** @noinspection PhpDocMissingThrowsInspection */
/** @noinspection PhpUnhandledExceptionInspection */


namespace HJerichen\ProphecyPHP;


use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use ReflectionException;
use ReflectionMethod;

/**
 * Trait PHPProphetTrait
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <h.jerichen@nordwest.com>
 */
trait PHPProphetTrait
{
    /**
     * @var PHPProphet
     */
    private $phpProphet;

    /**
     * @param string $namespace
     * @return NamespaceProphecy
     */
    private function prophesizePHP(string $namespace): NamespaceProphecy
    {
        return $this->getPHPProphet()->prophesize($namespace);
    }

    /**
     * @return PHPProphet
     */
    private function getPHPProphet(): PHPProphet
    {
        if ($this->phpProphet === null) {
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
     *
     */
    public function runBare(): void
    {
        /** @noinspection PhpUndefinedClassInspection */
        parent::runBare();

        $this->phpProphet->unReveal();
    }
}