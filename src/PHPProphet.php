<?php declare(strict_types=1);

namespace HJerichen\ProphecyPHP;

use Prophecy\Exception\Prediction\PredictionException;
use Prophecy\Prophet;
use SebastianBergmann\Template\Template;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class PHPProphet
{
    /** @var NamespaceProphecy[] */
    private array $namespaceProphecies = [];

    public function __construct(
        public readonly Prophet $prophet
    ) {
    }

    public function prophesize(string $namespace): NamespaceProphecy
    {
        $textTemplate = new Template(__DIR__ . '/function.tpl');
        $functionRevealer = new FunctionRevealer($textTemplate);
        $functionProphecyStorage = FunctionProphecyStorage::getInstance();

        $namespaceProphecy = new NamespaceProphecy(
            $functionProphecyStorage,
            $functionRevealer,
            $this->prophet,
            $namespace,
        );
        $this->namespaceProphecies[$namespace] = $namespaceProphecy;

        return $this->namespaceProphecies[$namespace];
    }

    /** @throws PredictionException */
    public function checkPredictions(): void
    {
        $this->prophet->checkPredictions();
    }

    public function unReveal(): void
    {
        foreach ($this->namespaceProphecies as $namespaceProphecy) {
            $namespaceProphecy->unReveal();
        }
    }

    public function __destruct()
    {
        $this->unReveal();
    }
}