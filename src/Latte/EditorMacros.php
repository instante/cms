<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Instante\CMS\Latte;

use Instante\CMS\DI\EditorExtension;
use Latte\Engine;
use Latte\Macros\MacroSet;
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
final class EditorMacros extends MacroSet
{
    const EDITABLE_TEXT_RESOLVER_FILTER = 'icms_editable_text_resolver';

    /** @var Engine */
    private $engine;

    private $inMacroText = FALSE;

    /**
     * EditorMacros constructor.
     */
    public function __construct(Engine $engine) {
        parent::__construct($engine->getCompiler());
        $this->engine = $engine;
    }

    public static function install(Engine $engine)
    {
        /** @var EditorMacros $me */
        $me = new static($engine);
        $me->addMacro('icms.text', [$me, 'macroTextBegin'], [$me, 'macroTextEnd']);
    }

    /**
     * Finishes template parsing.
     * @return array(prolog, epilog)
     */
    public function finalize()
    {
        return ['$_icmsetr = $this->getEngine()->getFilters()[\''.self::EDITABLE_TEXT_RESOLVER_FILTER.'\'];'];
    }

    /**
     * {icms-text ident}
     */
    public function macroTextBegin(MacroNode $node, PhpWriter $writer)
    {
        if ($node->prefix !== MacroNode::PREFIX_INNER && $node->prefix !== NULL) {
            throw new CompileException('icms.text macro currently doesn\'t support n:icms-text form, you may only use n:inner-icms.text');
        }
        if ($this->inMacroText) {
            throw new \LogicException('icms-text macros cannot be nested.');
        }
        $this->inMacroText = TRUE;
        return $writer->write('$icmstext = $_icmsetr(%node.word); if ($icmstext !== NULL) { echo %modify( $icmstext; ); } else {');
    }

    public function macroTextEnd(MacroNode $node, PhpWriter $writer)
    {
        $this->inMacroText = FALSE;
        return $writer->write('} unset ($icmstext);');
    }
}
