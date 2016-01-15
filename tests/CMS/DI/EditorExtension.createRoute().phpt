<?php

namespace Instante\Tests\CMS\DI;


use Instante\CMS\DI\EditorExtension;
use Nette\Application\IRouter;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

Assert::type(IRouter::class, EditorExtension::createRoute());
