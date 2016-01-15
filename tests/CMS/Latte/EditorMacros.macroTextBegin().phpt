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
$pw = PhpWriter::using($mn);
$f = $e->macroTextBegin($mn, $pw);
Assert::same('$icmstext = $_icmsetr("foo"); if ($icmstext !== NULL) { echo  $icmstext; ; } else {', $f);

//test disabled nesting
$e->macroTextEnd($mn, $pw);
$e->macroTextBegin($mn, $pw);
Assert::exception(function () use ($e, $mn, $pw) {
    $e->macroTextBegin($mn, $pw);
}, LogicException::class);
