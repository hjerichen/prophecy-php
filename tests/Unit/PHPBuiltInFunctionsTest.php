<?php /** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

namespace HJerichen\ProphecyPHP\Tests\Unit;

use HJerichen\ProphecyPHP\ArgumentEvaluator;
use HJerichen\ProphecyPHP\FunctionDelegation;
use HJerichen\ProphecyPHP\FunctionProphecy;
use HJerichen\ProphecyPHP\FunctionProphecyStorage;
use HJerichen\ProphecyPHP\FunctionRevealer;
use HJerichen\ProphecyPHP\NamespaceProphecy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class PHPBuiltInFunctionsTest extends TestCase
{
    use ProphecyTrait;

    private NamespaceProphecy $namespaceProphecy;

    /** @var ObjectProphecy<FunctionProphecyStorage>  */
    private ObjectProphecy $functionProphecyStorage;
    /** @var ObjectProphecy<FunctionDelegation>  */
    private ObjectProphecy $functionDelegation;
    /** @var ObjectProphecy<MethodProphecy>  */
    private ObjectProphecy $methodProphecy;

    private MockObject $objectProphecy;

    private string $namespace;

    protected function setUp(): void
    {
        parent::setUp();

        $this->functionDelegation = $this->prophesize(FunctionDelegation::class);
        $this->objectProphecy = $this->createMock(ObjectProphecy::class);
        /** @psalm-suppress MixedMethodCall */
        $this->objectProphecy->method('reveal')->willReturn($this->functionDelegation->reveal());
        $this->methodProphecy = $this->prophesize(MethodProphecy::class);

        $prophet = $this->createMock(Prophet::class);
        $prophet->method('prophesize')
            ->with(FunctionDelegation::class)
            ->willReturn($this->objectProphecy);

        $this->functionProphecyStorage = $this->prophesize(FunctionProphecyStorage::class);
        $this->namespace = 'namespace';

        $functionRevealer = $this->prophesize(FunctionRevealer::class);

        $this->namespaceProphecy = new NamespaceProphecy(
            $this->functionProphecyStorage->reveal(),
            $functionRevealer->reveal(),
            $prophet,
            $this->namespace,
        );
    }

    /**
     * @param string $functionName
     * @param array $arguments
     */
    private function setUpCallTest(string $functionName, array $arguments): void
    {
        $expectedFunctionProphecy = new FunctionProphecy(
            $this->functionDelegation->reveal(),
            new ArgumentEvaluator($arguments),
            $functionName,
            $this->namespace,
            $arguments
        );

        $this->functionProphecyStorage
            ->add($expectedFunctionProphecy)
            ->shouldBeCalledOnce();
        $this->objectProphecy
            ->method('__call')
            ->with('delegate', [$functionName, $arguments])
            ->willReturn($this->methodProphecy->reveal());
    }


    /* TESTS */

    public function testFileGetContents(): void
    {
        $this->setUpCallTest('file_get_contents', ['file']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->file_get_contents('file');
        self::assertEquals($expected, $actual);
    }

    public function testFilePutContents(): void
    {
        $this->setUpCallTest('file_put_contents', ['filename', 'content']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->file_put_contents('filename', 'content');
        self::assertEquals($expected, $actual);
    }

    public function testScanDir(): void
    {
        $this->setUpCallTest('scandir', ['dir']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->scandir('dir');
        self::assertEquals($expected, $actual);
    }

    public function testIsFile(): void
    {
        $this->setUpCallTest('is_file', ['file']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->is_file('file');
        self::assertEquals($expected, $actual);
    }

    public function testTouch(): void
    {
        $this->setUpCallTest('touch', ['file']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->touch('file');
        self::assertEquals($expected, $actual);
    }

    public function testUnlink(): void
    {
        $this->setUpCallTest('unlink', ['file']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->unlink('file');
        self::assertEquals($expected, $actual);
    }

    public function testCopy(): void
    {
        $this->setUpCallTest('copy', ['file1', 'file2']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->copy('file1', 'file2');
        self::assertEquals($expected, $actual);
    }

    public function testStrToTime(): void
    {
        $this->setUpCallTest('strtotime', ['time']);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->strtotime('time');
        self::assertEquals($expected, $actual);
    }

    public function testTime(): void
    {
        $this->setUpCallTest('time', []);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->time();
        self::assertEquals($expected, $actual);
    }

    public function testMemoryGetPeakUsage(): void
    {
        $this->setUpCallTest('memory_get_peak_usage', []);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->memory_get_peak_usage();
        self::assertEquals($expected, $actual);
    }

    public function testSleep(): void
    {
        $this->setUpCallTest('sleep', [2]);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->sleep(2);
        self::assertEquals($expected, $actual);
    }

    public function testUSleep(): void
    {
        $this->setUpCallTest('usleep', [2]);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->usleep(2);
        self::assertEquals($expected, $actual);
    }

    public function testPcntlAsyncSignals(): void
    {
        $this->setUpCallTest('pcntl_async_signals', [true]);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->pcntl_async_signals(true);
        self::assertEquals($expected, $actual);
    }

    public function testPcntlSignal(): void
    {
        $arguments = [2, static function (): void {}];
        $this->setUpCallTest('pcntl_signal', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->pcntl_signal(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testPosixGetPid(): void
    {
        $arguments = [];
        $this->setUpCallTest('posix_getpid', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->posix_getpid(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testPosixSetSid(): void
    {
        $arguments = [];
        $this->setUpCallTest('posix_setsid', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->posix_setsid(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testPosixGetSid(): void
    {
        $arguments = [10];
        $this->setUpCallTest('posix_getsid', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->posix_getsid(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testGetMyPid(): void
    {
        $arguments = [];
        $this->setUpCallTest('getmypid', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->getmypid(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testExec(): void
    {
        $arguments = ['command'];
        $this->setUpCallTest('exec', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->exec(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testUnserialize(): void
    {
        $arguments = ['string'];
        $this->setUpCallTest('unserialize', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->unserialize(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testVarExport(): void
    {
        $arguments = ['string'];
        $this->setUpCallTest('var_export', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->var_export(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testGetClass(): void
    {
        $arguments = [$this];
        $this->setUpCallTest('get_class', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->get_class(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testDnsGetRecord(): void
    {
        $arguments = ['localhost'];
        $this->setUpCallTest('dns_get_record', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->dns_get_record(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testFileExists(): void
    {
        $arguments = ['file'];
        $this->setUpCallTest('file_exists', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->file_exists(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testFileMTime(): void
    {
        $arguments = ['file'];
        $this->setUpCallTest('filemtime', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->filemtime(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testMicrotime(): void
    {
        $arguments = [true];
        $this->setUpCallTest('microtime', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->microtime(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testDate(): void
    {
        $this->setUpCallTest('date', ['Y', 1234]);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->date('Y', 1234);
        self::assertEquals($expected, $actual);
    }

    public function testUniqId(): void
    {
        $arguments = ['localhost'];
        $this->setUpCallTest('uniqid', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->uniqid(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testIsDir(): void
    {
        $arguments = ['dir'];
        $this->setUpCallTest('is_dir', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->is_dir(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testMkdir(): void
    {
        $arguments = ['localhost'];
        $this->setUpCallTest('mkdir', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->mkdir(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testGcDisable(): void
    {
        $arguments = [];
        $this->setUpCallTest('gc_disable', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->gc_disable(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testGcEnable(): void
    {
        $arguments = [];
        $this->setUpCallTest('gc_enable', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->gc_enable(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testFWrite(): void
    {
        $resource = fopen('php://memory', 'rb+');
        assert($resource !== false);

        $arguments = [$resource, 'string'];
        $this->setUpCallTest('fwrite', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->fwrite($resource, 'string');
        self::assertEquals($expected, $actual);
    }

    public function testFOpen(): void
    {
        $arguments = ['file', 'a'];
        $this->setUpCallTest('fopen', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->fopen(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testMail(): void
    {
        $arguments = ['to', 'subject', 'message'];
        $this->setUpCallTest('mail', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->mail(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testChmod(): void
    {
        $arguments = ['file', 0777];
        $this->setUpCallTest('chmod', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->chmod(...$arguments);
        self::assertEquals($expected, $actual);
    }

    public function testSessionWriteClose(): void
    {
        $arguments = [];
        $this->setUpCallTest('session_write_close', $arguments);

        $expected = $this->methodProphecy->reveal();
        $actual = $this->namespaceProphecy->session_write_close(...$arguments);
        self::assertEquals($expected, $actual);
    }
}
