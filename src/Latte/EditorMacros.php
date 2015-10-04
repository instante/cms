<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Instante\CMS\Latte;

use Latte\Macros\MacroSet;
use Nette;
use Latte;
use Latte\MacroNode;
use Latte\PhpWriter;
use Latte\CompileException;
use Nette\Utils\Strings;


/**
 * Macros for Nette\Application\UI.
 *
 * - {link destination ...} control link
 * - {plink destination ...} presenter link
 * - {snippet ?} ... {/snippet ?} control snippet
 */
class EditorMacros extends MacroSet
{

    public static function install(Latte\Compiler $compiler)
    {
        $me = new static($compiler);
        $me->addMacro('icms-text', array($me, 'macroTextBegin'), array($me, 'macroTextEnd'), array($me, 'macroTextInline'));

        /*$me->addMacro('href', NULL, NULL, function (MacroNode $node, PhpWriter $writer) use ($me) {
            return ' ?> href="<?php ' . $me->macroLink($node, $writer) . ' ?>"<?php ';
        });*/
    }


    /**
     * {icms-text ident}
     */
    public function macroTextBegin(MacroNode $node, PhpWriter $writer)
    {
        return 'icms-begin';
        $words = $node->tokenizer->fetchWords();
        if (!$words) {
            throw new CompileException('Missing control name in {control}');
        }
        $name = $writer->formatWord($words[0]);
        $method = isset($words[1]) ? ucfirst($words[1]) : '';
        $method = Strings::match($method, '#^\w*\z#') ? "render$method" : "{\"render$method\"}";
        $param = $writer->formatArray();
        if (!Strings::contains($node->args, '=>')) {
            $param = substr($param, $param[0] === '[' ? 1 : 6, -1); // removes array() or []
        }
        return ($name[0] === '$' ? "if (is_object($name)) \$_l->tmp = $name; else " : '')
        . '$_l->tmp = $_control->getComponent(' . $name . '); '
        . 'if ($_l->tmp instanceof Nette\Application\UI\IRenderable) $_l->tmp->redrawControl(NULL, FALSE); '
        . ($node->modifiers === '' ? "\$_l->tmp->$method($param)" : $writer->write("ob_start(); \$_l->tmp->$method($param); echo %modify(ob_get_clean())"));
    }

    public function macroTextEnd(MacroNode $node, PhpWriter $writer)
    {
        return 'icms-end';
    }

    public function macroTextInline(MacroNode $node, PhpWriter $writer)
    {
        return 'icms-inline';
    }
}
