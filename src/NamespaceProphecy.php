<?php


namespace HJerichen\ProphecyPHP;


use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ProphecyInterface;
use Prophecy\Prophet;
use Text_Template;

/**
 * Class NamespaceProphecy
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <h.jerichen@nordwest.com>
 */
class NamespaceProphecy implements ProphecyInterface
{
    use PHPBuiltInFunctions;

    /**
     * @var Prophet
     */
    private $prophet;
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var FunctionProphecyStorage
     */
    private $functionProphecyStorage;
    /**
     * @var Text_Template
     */
    private $textTemplate;


    /**
     * NamespaceProphecy constructor.
     * @param Prophet $prophet
     * @param string $namespace
     * @param FunctionProphecyStorage $functionProphecyStorage
     * @param Text_Template $textTemplate
     */
    public function __construct(Prophet $prophet, string $namespace, FunctionProphecyStorage $functionProphecyStorage, Text_Template $textTemplate)
    {
        $this->prophet = $prophet;
        $this->namespace = $namespace;
        $this->functionProphecyStorage = $functionProphecyStorage;
        $this->textTemplate = $textTemplate;
    }

    /**
     * @param string $functionName
     * @param array $arguments
     * @return MethodProphecy
     */
    public function __call(string $functionName, array $arguments): MethodProphecy
    {
        $prophecy = $this->prophet->prophesize(FunctionDelegation::class);

        /** @noinspection PhpParamsInspection */
        $functionProphecy = new FunctionProphecy($prophecy->reveal(), $this->namespace, $functionName, $arguments);
        $this->functionProphecyStorage->add($functionProphecy);

        return $prophecy->__call('delegate', [$functionName, $arguments]);
    }

    /**
     * Reveals prophecy object (double) .
     */
    public function reveal(): void
    {
        foreach ($this->functionProphecyStorage->getFunctionNamesOfSetProphecies($this->namespace) as $functionName) {
            $this->revealFunction($functionName);
        }
    }

    /**
     * @param string $functionName
     */
    private function revealFunction(string $functionName): void
    {
        if ($this->isFunctionAlreadyRevealed($functionName)) {
            return;
        }

        $data = [
            'namespace' => $this->namespace,
            'functionName' => $functionName
        ];
        $this->textTemplate->setVar($data, false);
        $renderedTemplate = $this->textTemplate->render();

        eval($renderedTemplate);
    }

    /**
     * @param string $functionName
     * @return bool
     */
    private function isFunctionAlreadyRevealed(string $functionName): bool
    {
        return function_exists("{$this->namespace}\\{$functionName}");
    }

    /**
     *
     */
    public function unReveal(): void
    {
        $this->functionProphecyStorage->removeFunctionPropheciesForNamespace($this->namespace);
    }
}