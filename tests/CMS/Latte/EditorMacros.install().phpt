<?php

namespace Instante\Tests\CMS\DI;


use Instante\CMS\Latte\EditorMacros;
use Latte\Compiler;
use Latte\Engine;
use Latte\IMacro;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class MockCompiler extends Compiler
{
    public $addedMacros = [];

    public function addMacro($name, IMacro $macro)
    {
        $this->addedMacros[$name] = $macro;
        return parent::addMacro($name, $macro);
    }
}

$e = new Engine;
$p = (new \ReflectionClass($e))->getProperty('compiler');
$p->setAccessible(TRUE);
$p->setValue($e, $mc = new MockCompiler());

EditorMacros::install($e);
Assert::true(isset($mc->addedMacros['icms.text']));
