<?php


namespace HJerichen\ProphecyPHP;


use Prophecy\Exception\Prediction\PredictionException;
use Prophecy\Prophet;
use Text_Template;

/**
 * Class PHPProphet
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <h.jerichen@nordwest.com>
 */
class PHPProphet
{
    /**
     * @var Prophet
     */
    private $prophet;
    /**
     * @var NamespaceProphecy[]
     */
    private $namespaceProphecies = [];

    /**
     * PHPProphet constructor.
     * @param Prophet $prophet
     */
    public function __construct(Prophet $prophet)
    {
        $this->prophet = $prophet;
    }

    /**
     * @param string $namespace
     * @return NamespaceProphecy
     */
    public function prophesize(string $namespace): NamespaceProphecy
    {
        $textTemplate = new Text_Template(__DIR__ . '/function.tpl');
        $functionProphecyStorage = FunctionProphecyStorage::getInstance();
        $functionRevealer = new FunctionRevealer($textTemplate);

        $this->namespaceProphecies[$namespace] = new NamespaceProphecy($this->prophet, $namespace, $functionProphecyStorage, $functionRevealer);
        return $this->namespaceProphecies[$namespace];
    }

    /**
     *
     * @throws PredictionException
     */
    public function checkPredictions(): void
    {
        $this->prophet->checkPredictions();
    }

    /*
     *
     */
    public function unReveal(): void
    {
        foreach ($this->namespaceProphecies as $namespaceProphecy) {
            $namespaceProphecy->unReveal();
        }
    }
}