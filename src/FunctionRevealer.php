<?php declare(strict_types=1);

namespace HJerichen\ProphecyPHP;

use SebastianBergmann\Template\Template;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 * @noinspection PhpClassCanBeReadonlyInspection
 */
class FunctionRevealer
{
    public function __construct(
        private readonly Template $textTemplate
    ) {
    }

    /** @psalm-suppress PossiblyUnusedParam */
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
        return function_exists("$namespace\\$functionName");
    }
}