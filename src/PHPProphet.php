<?php

namespace HJerichen\ProphecyPHP;

use Prophecy\Prophet;
use SebastianBergmann\Template\Template;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class PHPProphet
{
    /** @var Prophet */
    private $prophet;
    /** @var NamespaceProphecy[] */
    private $namespaceProphecies = [];

    public function __construct(Prophet $prophet)
    {
        $this->prophet = $prophet;
    }

    public function prophesize(string $namespace): NamespaceProphecy
    {
        $textTemplate = new Template(__DIR__ . '/function.tpl');
        $functionRevealer = new FunctionRevealer($textTemplate);
        $functionProphecyStorage = FunctionProphecyStorage::getInstance();

        $namespaceProphecy = new NamespaceProphecy(
            $this->prophet,
            $namespace,
            $functionProphecyStorage,
            $functionRevealer
        );
        $this->namespaceProphecies[$namespace] = $namespaceProphecy;

        return $this->namespaceProphecies[$namespace];
    }

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
}