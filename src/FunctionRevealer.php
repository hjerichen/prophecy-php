<?php


namespace HJerichen\ProphecyPHP;


use Text_Template;

/**
 * Class FunctionRevealer
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionRevealer
{
    /**
     * @var Text_Template
     */
    private $textTemplate;

    /**
     * FunctionRevealer constructor.
     * @param Text_Template $textTemplate
     */
    public function __construct(Text_Template $textTemplate)
    {
        $this->textTemplate = $textTemplate;
    }

    /**
     * @param string $namespace
     * @param string $functionName
     */
    public function revealFunction(string $namespace, string $functionName): void
    {
        if ($this->isFunctionAlreadyRevealed($namespace, $functionName)) {
            return;
        }

        $data = [
            'namespace' => $namespace,
            'functionName' => $functionName
        ];
        $this->textTemplate->setVar($data, false);
        $renderedTemplate = $this->textTemplate->render();

        eval($renderedTemplate);
    }

    /**
     * @param string $namespace
     * @param string $functionName
     * @return bool
     */
    private function isFunctionAlreadyRevealed(string $namespace, string $functionName): bool
    {
        return function_exists("{$namespace}\\{$functionName}");
    }
}