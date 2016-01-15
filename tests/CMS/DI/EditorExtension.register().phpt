<?php

namespace Instante\Tests\CMS\DI;


use Instante\CMS\DI\EditorExtension;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\ContainerBuilder;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

$c = new Configurator;
$com = new Compiler(new ContainerBuilder);
EditorExtension::register($c);
$c->onCompile($c, $com);
Assert::count(1, $com->getExtensions(EditorExtension::class));
