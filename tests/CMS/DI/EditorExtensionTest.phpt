<?php

namespace Instante\Tests\CMS\DI;


use Instante\CMS\DI\EditorExtension;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

class EditorExtensionTest extends TestCase
{
    public function testInstantiate()
    {
        $e = new EditorExtension;
        Assert::type('Instante\CMS\DI\EditorExtension', $e);
    }
}

run(new EditorExtensionTest);
