<?php

namespace Instante\Tests\CMS\DI;


use Instante\CMS\Latte\EditorMacros;
use Latte\Compiler;
use Latte\Engine;
use Latte\IMacro;
use Latte\MacroNode;
use Latte\MacroTokens;
use Latte\PhpWriter;
use LogicException;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class MockMacro implements IMacro
{

    function initialize()
    {
    }

    function finalize()
    {
    }

    function nodeOpened(MacroNode $node)
    {
    }

    function nodeClosed(MacroNode $node)
    {
    }
}

$e = EditorMacros::install(new Engine);

//test basic opening macro
$mn = new MacroNode(new MockMacro, 'icms.text', 'foo');
$f = $e->macroTextEnd($mn, PhpWriter::using($mn));
Assert::same('} unset ($icmstext);', $f);

