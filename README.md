# Prophecy-PHP
Mock build-in PHP functions for PHPUnit in Prophecy Style.

## Installation
Use [Composer](https://getcomposer.org/):
```sh
composer require hjerichen/prophecy-php
```

## Usage
Use the trait PHPProphetTrait in PHPUnit Test Cases.
```php
<?php

namespace Some\Space;

use PHPUnit\Framework\TestCase;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use HJerichen\ProphecyPHP\NamespaceProphecy;

class SomeTest extends TestCase
{
    use PHPProphetTrait;

    /**
     * @var NamespaceProphecy
     */
    private $php;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->php = $this->prophesizePHP(__NAMESPACE__);
    }

    /**
     *
     */
    public function testSomething(): void
    {
        $this->php->time()->willReturn(2);
        $this->php->reveal();

        $this->assertEquals(2, time());
    }
}
```

Everything works like you know it from Prophecy:
```php
<?php

$this->php->time()->willReturn(1234, 1235, 1236);

$this->php->date('Y', 1234)->willReturn('1970');
$this->php->date('Y', 1234000)->willReturn('1971');

$this->php->file_put_contents('/to/foo.txt', 'some content')->shouldBeCalledOnce();
$this->php->file_put_contents('/to/foo.txt2', 'some content')->shouldBeCalledOnce();

$this->php->file_put_contents('/to/foo.txt', \Prophecy\Argument::any())->shouldNotBeCalled();

$this->php->reveal(); //Only with this call the above functions will be mocked.
```

## Restrictions

Only *unqualified* function calls in a namespace context can be mocked.


## License and authors

This project is free and under the MIT Licence.
Responsible for this project is Heiko Jerichen (heiko@jerichen.de).