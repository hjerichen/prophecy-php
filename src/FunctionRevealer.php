<?php

namespace HJerichen\ProphecyPHP;

use SebastianBergmann\Template\Template;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionRevealer
{
    private Template $textTemplate;

    public function __construct(Template $textTemplate)
    {
        $this->textTemplate = $textTemplate;
    }

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

    private function isFunctionAlreadyRevealed(string $namespace, string $functionName): bool
    {
        return function_exists("{$namespace}\\{$functionName}");
    }
}