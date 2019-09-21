<?php

namespace HJerichen\ProphecyPHP;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

/**
 * Class PHPBuiltInFunctionsTest
 * @package HJerichen\ProphecyPHP
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class PHPBuiltInFunctionsTest extends TestCase
{
    /**
     * @var NamespaceProphecy
     */
    private $namespaceProphecy;
    /**
     * @var Prophet | ObjectProphecy
     */
    private $prophet;
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var FunctionProphecyStorage | ObjectProphecy
     */
    private $functionProphecyStorage;
    /**
     * @var FunctionRevealer | ObjectProphecy
     */
    private $functionRevealer;
    /**
     * @var ObjectProphecy | MockObject
     */
    private $objectProphecy;
    /**
     * @var FunctionDelegation | ObjectProphecy
     */
    private $functionDelegation;
    /**
     * @var MethodProphecy | ObjectProphecy
     */
    private $methodProphecy;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->functionDelegation = $this->prophesize(FunctionDelegation::class);
        $this->objectProphecy = $this->createMock(ObjectProphecy::class);
        $this->objectProphecy->method('reveal')->willReturn($this->functionDelegation->reveal());
        $this->methodProphecy = $this->prophesize(MethodProphecy::class);

        $this->prophet = $this->createMock(Prophet::class);
        $this->prophet->method('prophesize')->with(FunctionDelegation::class)->willReturn($this->objectProphecy);

        $this->functionProphecyStorage = $this->prophesize(FunctionProphecyStorage::class);
        $this->functionRevealer = $this->prophesize(FunctionRevealer::class);
        $this->namespace = 'namespace';

        $this->namespaceProphecy = new NamespaceProphecy($this->prophet, $this->namespace, $this->functionProphecyStorage->reveal(), $this->functionRevealer->reveal());
    }

    /**
     * @param string $functionName
     * @param array $arguments
     */
    private function setUpCallTest(string $functionName, array $arguments): void
    {
        $expectedFunctionProphecy = new FunctionProphecy($this->functionDelegation->reveal(), new ArgumentEvaluator($arguments), $this->namespace, $functionName, $arguments);

        $this->functionProphecyStorage->add($expectedFunctionProphecy)->shouldBeCalledOnce();
        $this->objectProphecy->method('__call')->with('delegate', [$functionName, $arguments])->willReturn($this->methodProphecy->reveal());
    }


    /* TESTS */

    /**
     *
     */
    public function testFileGetContents(): void
    {
        $this->setUpCallTest('file_get_contents', ['file']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->file_get_contents('file');
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testFilePutContents(): void
    {
        $this->setUpCallTest('file_put_contents', ['filename', 'content']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->file_put_contents('filename', 'content');
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testScanDir(): void
    {
        $this->setUpCallTest('scandir', ['dir']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->scandir('dir');
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testIsFile(): void
    {
        $this->setUpCallTest('is_file', ['file']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->is_file('file');
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testTouch(): void
    {
        $this->setUpCallTest('touch', ['file']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->touch('file');
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testUnlink(): void
    {
        $this->setUpCallTest('unlink', ['file']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->unlink('file');
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testCopy(): void
    {
        $this->setUpCallTest('copy', ['file1', 'file2']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->copy('file1', 'file2');
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testStrToTime(): void
    {
        $this->setUpCallTest('strtotime', ['time']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->strtotime('time');
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testTime(): void
    {
        $this->setUpCallTest('time', []);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->time();
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testMemoryGetPeakUsage(): void
    {
        $this->setUpCallTest('memory_get_peak_usage', []);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->memory_get_peak_usage();
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testSleep(): void
    {
        $this->setUpCallTest('sleep', [2]);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->sleep(2);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testUSleep(): void
    {
        $this->setUpCallTest('usleep', [2]);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->usleep(2);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testPcntlAsyncSignals(): void
    {
        $this->setUpCallTest('pcntl_async_signals', [true]);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->pcntl_async_signals(true);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testPcntlSignal(): void
    {
        $arguments = [
            2,
            static function () {
            }
        ];
        $this->setUpCallTest('pcntl_signal', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->pcntl_signal(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testPosixGetPid(): void
    {
        $arguments = [];
        $this->setUpCallTest('posix_getpid', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->posix_getpid(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testPosixSetSid(): void
    {
        $arguments = [];
        $this->setUpCallTest('posix_setsid', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->posix_setsid(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testPosixGetSid(): void
    {
        $arguments = [10];
        $this->setUpCallTest('posix_getsid', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->posix_getsid(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testGetMyPid(): void
    {
        $arguments = [];
        $this->setUpCallTest('getmypid', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->getmypid(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testExit(): void
    {
        $arguments = [];
        $this->setUpCallTest('exit', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->exit(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testExec(): void
    {
        $arguments = ['command'];
        $this->setUpCallTest('exec', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->exec(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testUnserialize(): void
    {
        $arguments = ['string'];
        $this->setUpCallTest('unserialize', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->unserialize(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testVarExport(): void
    {
        $arguments = ['string'];
        $this->setUpCallTest('var_export', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->var_export(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testGetClass(): void
    {
        $arguments = [$this];
        $this->setUpCallTest('get_class', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->get_class(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testDnsGetRecord(): void
    {
        $arguments = ['localhost'];
        $this->setUpCallTest('dns_get_record', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->dns_get_record(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testFileExists(): void
    {
        $arguments = ['file'];
        $this->setUpCallTest('file_exists', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->file_exists(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testFileMTime(): void
    {
        $arguments = ['file'];
        $this->setUpCallTest('filemtime', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->filemtime(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testMicrotime(): void
    {
        $arguments = [true];
        $this->setUpCallTest('microtime', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->microtime(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testDate(): void
    {
        $this->setUpCallTest('date', ['Y', 1234]);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->date('Y', 1234);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testUniqId(): void
    {
        $arguments = ['localhost'];
        $this->setUpCallTest('uniqid', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->uniqid(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testIsDir(): void
    {
        $arguments = ['dir'];
        $this->setUpCallTest('is_dir', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->is_dir(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testMkdir(): void
    {
        $arguments = ['localhost'];
        $this->setUpCallTest('mkdir', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->mkdir(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testGcDisable(): void
    {
        $arguments = [];
        $this->setUpCallTest('gc_disable', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->gc_disable(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testGcEnable(): void
    {
        $arguments = [];
        $this->setUpCallTest('gc_enable', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->gc_enable(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testFWrite(): void
    {
        $arguments = ['localhost', 'string'];
        $this->setUpCallTest('fwrite', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->fwrite(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testFOpen(): void
    {
        $arguments = ['file', 'a'];
        $this->setUpCallTest('fopen', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->fopen(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testMail(): void
    {
        $arguments = ['to', 'subject', 'message'];
        $this->setUpCallTest('mail', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->mail(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testChmod(): void
    {
        $arguments = ['file', 0777];
        $this->setUpCallTest('chmod', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->chmod(...$arguments);
        $this->assertEquals($expected, $actual);
    }

    /**
     *
     */
    public function testSessionWriteClose(): void
    {
        $arguments = [];
        $this->setUpCallTest('session_write_close', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->session_write_close(...$arguments);
        $this->assertEquals($expected, $actual);
    }
}
