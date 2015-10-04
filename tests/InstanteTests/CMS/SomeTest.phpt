<?php

namespace InstanteTests\CMS;


use Instante\Forms\FormControlHtmlDecorator;
use Nette\Forms\Controls\TextInput;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

class SomeTest extends TestCase
{

    public function testDataFlag()
    {
        Assert::true(TRUE);
    }
}

run(new SomeTest);
