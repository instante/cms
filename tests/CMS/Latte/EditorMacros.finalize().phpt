<?php

namespace Instante\Tests\CMS\DI;


use Instante\CMS\Latte\EditorMacros;
use Latte\Engine;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

$e = EditorMacros::install(new Engine);
$f = $e->finalize();
Assert::type('array', $f);
Assert::count(1, $f);
