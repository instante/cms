<?php

namespace Instante\Tests\CMS\DI;


use Instante\CMS\DI\EditorExtension;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

$e = new EditorExtension;
Assert::true(in_array('Instante\\CMS\\Model', array_keys($e->getEntityMappings())));
