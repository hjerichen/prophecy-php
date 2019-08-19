<?php

namespace HJerichen\ProphecyPHP;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Text_Template;

/**
 * Class FunctionRevealerTest
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class FunctionRevealerTest extends TestCase
{
    /**
     * @var FunctionRevealer
     */
    private $functionRevealer;
    /**
     * @var Text_Template | ObjectProphecy
     */
    private $textTemplate;
    /**
     * @var string
     */
    private $namespace;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->textTemplate = $this->prophesize(Text_Template::class);
        $this->namespace = 'namespace';

        $GLOBALS['NamespaceProphecyTest::FunctionExists::Active'] = true;

        $this->functionRevealer = new FunctionRevealer($this->textTemplate->reveal());
    }

    /**
     *
     */
    protected function tearDown(): void
    {
        $GLOBALS['NamespaceProphecyTest::FunctionExists::Active'] = false;
    }


    /* TESTS */

    /**
     *
     */
    public function testRevealFunction(): void
    {
        $expectedTemplateData = [
            'namespace' => $this->namespace,
            'functionName' => 'time'
        ];
        $this->textTemplate->setVar($expectedTemplateData, false)->shouldBeCalledOnce();
        $this->textTemplate->render()->willReturn('$_SESSION["test1"] = 1;');

        $this->functionRevealer->revealFunction($this->namespace, 'time');
        $this->assertEquals(1, $_SESSION['test1']);
    }

    /**
     *
     */
    public function testRevealWithFunctionAlreadyRevealed(): void
    {
        $this->textTemplate->render()->shouldNotBeCalled();

        $this->functionRevealer->revealFunction($this->namespace, 'count');
    }


    /* HELPERS */
}


/**
 * Mock for function "function_exists"
 * @param string $functionName
 * @return bool
 */
function function_exists(string $functionName): bool
{
    if ($GLOBALS['NamespaceProphecyTest::FunctionExists::Active'] === false) {
        return \function_exists($functionName);
    }

    switch ($functionName) {
        case 'namespace\time':
            return false;
        case 'namespace\count':
            return true;
    }

    throw new InvalidArgumentException("FunctionName \"{$functionName}\" not supported in \"function_exists\".");
}
$GLOBALS['NamespaceProphecyTest::FunctionExists::Active'] = false;
