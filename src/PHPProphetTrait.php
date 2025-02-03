<?php declare(strict_types=1);

namespace HJerichen\ProphecyPHP;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\PostCondition;
use PHPUnit\Framework\TestCase;
use Prophecy\Exception\Prediction\PredictionException;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophet;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
trait PHPProphetTrait
{
    private PHPProphet $phpProphet;
    private bool $phpProphecyAssertionsCounted = false;

    private function prophesizePHP(string $namespace): NamespaceProphecy
    {
        return $this->getPHPProphet()->prophesize($namespace);
    }

    /**
     * @postCondition
     * @psalm-suppress PossiblyUnusedMethod
     */
    #[PostCondition]
    protected function verifyPhpProphecyDoubles(): void
    {
        if ($this->prophecyIsUsed()) return;
        if (!$this->phpProphecyIsUsed()) return;

        try {
            $this->phpProphet->checkPredictions();
        } catch (PredictionException $e) {
            /** @psalm-suppress InternalClass, InternalMethod */
            throw new AssertionFailedError($e->getMessage());
        } finally {
            $this->countProphecyAssertions();
        }
    }

    /**
     * @after
     * @psalm-suppress PossiblyUnusedMethod
     */
    #[After]
    protected function tearDownPhpProphecy(): void
    {
        try {
            if ($this->prophecyIsUsed()) return;
            if (!$this->phpProphecyIsUsed()) return;
            if ($this->phpProphecyAssertionsCounted) return;
            $this->countProphecyAssertions();
        } finally {
            unset($this->phpProphet);
        }
    }

    private function getPHPProphet(): PHPProphet
    {
        if (!isset($this->phpProphet)) {
            $this->phpProphet = new PHPProphet($this->getProphetFromTestCase());
        }
        return $this->phpProphet;
    }

    private function getProphetFromTestCase(): Prophet
    {
        return $this->prophet ?? new Prophet();
    }

    private function prophecyIsUsed(): bool
    {
        return isset($this->prophet) || isset($this->prophecyAssertionsCounted);
    }

    private function phpProphecyIsUsed(): bool
    {
        return isset($this->phpProphet);
    }

    /** @internal */
    private function countProphecyAssertions(): void
    {
        assert($this instanceof TestCase);
        assert(isset($this->phpProphet));
        $this->phpProphecyAssertionsCounted = true;

        foreach ($this->phpProphet->prophet->getProphecies() as $objectProphecy) {
            foreach ($objectProphecy->getMethodProphecies() as $methodProphecies) {
                foreach ($methodProphecies as $methodProphecy) {
                    /** @psalm-suppress InternalMethod */
                    $this->addToAssertionCount(count($methodProphecy->getCheckedPredictions()));
                }
            }
        }
    }
}
