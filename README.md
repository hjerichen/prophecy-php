[![Build Status](https://travis-ci.org/hjerichen/prophecy-php.svg?branch=master)](https://travis-ci.org/hjerichen/prophecy-php)
[![Coverage Status](https://coveralls.io/repos/github/hjerichen/prophecy-php/badge.svg?branch=master)](https://coveralls.io/github/hjerichen/prophecy-php?branch=master)

# Prophecy-PHP
Mock build-in PHP functions for PHPUnit in Prophecy Style.

## Installation
Use [Composer](https://getcomposer.org/):
```sh
composer require --dev hjerichen/prophecy-php
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

    /** @var NamespaceProphecy */
    private $php;

    public function setUp(): void
    {
        parent::setUp();

        $this->php = $this->prophesizePHP(__NAMESPACE__);
    }

    public function testSomething(): void
    {
        $this->php->time()->willReturn(2);
        $this->php->reveal();

        self::assertEquals(2, time());
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

## Known Issues

Because auf the PHP Bug [64346](https://bugs.php.net/bug.php?id=64346) mocking may not work. This is because of calling the original function in the namesapce before mocking it.
In this case, you could try to use "prepare" in the "setUp" method:
```php
<?php

$this->php->prepare('time', 'date'); //If you have problems with the time and date functions.
```

## License and authors

This project is free and under the MIT Licence.
Responsible for this project is Heiko Jerichen (heiko@jerichen.de).