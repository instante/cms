<?php

namespace Instante\Tests\CMS\DI;


use Instante\CMS\DI\EditorExtension;
use Instante\CMS\Editor\EditableFacade;
use Nette\DI\Compiler;
use Nette\DI\ContainerBuilder;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


$e = new EditorExtension;
$e->setCompiler(new Compiler($cb = new ContainerBuilder()), 'c');

//test latte factory is set up
$lf = $cb->addDefinition('latte.latteFactory');
Assert::count(0, $lf->getSetup());
$e->loadConfiguration();
Assert::count(2, $lf->getSetup());

//test editable facade service was added
Assert::same(EditableFacade::class, $cb->getDefinition($e->prefix(EditorExtension::EDITABLE_FACADE_SERVICE))->getClass());
