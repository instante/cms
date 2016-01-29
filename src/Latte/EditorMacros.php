<?php

namespace Instante\CMS\Latte;

use Instante\CMS\Editor\IICMSAuthorizator;
use Latte\CompileException;
use Latte\Engine;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

/**
 * Macros for Nette\Application\UI.
 *
 * - {icms.text $ident}default text{/icms.text}
 * - <element n:inner-icms.text="$ident">default text</element>
 */
final class EditorMacros extends MacroSet
{
    const EDITABLE_TEXT_AUTHORIZATOR = 'icms_editable_autorizator';
    const EDITABLE_TEXT_RESOLVER_FILTER = 'icms_editable_text_resolver';
    const EDITABLE_TEXT_CONTAINER_CLASS = 'icms-editable';
    const EDITABLE_TEXT_CONTENT_CLASS = 'icms-editable-content';
    const EDITABLE_TEXT_EDIT_BUTTON_CLASS = 'icms-editable-button-edit';
    const BUTTON_TEXT = 'Upravit';
    /** @var Engine */
    private $engine;
    /** @var IICMSAuthorizator */
    private $authorizator;
    private $inMacroText = FALSE;

    /**
     * EditorMacros constructor.
     */
    public function __construct(Engine $engine, IICMSAuthorizator $authorizator)
    {
        parent::__construct($engine->getCompiler());
        $this->engine = $engine;
        $this->authorizator = $authorizator;
    }

    public static function install(Engine $engine, IICMSAuthorizator $authorizator)
    {
        /** @var EditorMacros $me */
        $me = new static($engine, $authorizator);
        $me->addMacro('icms.text', [$me, 'macroTextBegin'], [$me, 'macroTextEnd']);
        return $me;
    }

    /**
     * Finishes template parsing.
     *
     * @return array(prolog, epilog)
     */
    public function finalize()
    {
        return ['$_icmsetr = $this->getEngine()->getFilters()[\'' . self::EDITABLE_TEXT_RESOLVER_FILTER . '\'];
        $_icmsea = $this->getEngine()->getFilters()[\'' . self::EDITABLE_TEXT_AUTHORIZATOR . '\'];'];
    }

    /**
     * {icms-text ident}
     */
    public function macroTextBegin(MacroNode $node, PhpWriter $writer)
    {
        if ($node->prefix !== MacroNode::PREFIX_INNER && $node->prefix !== NULL) {
            throw new CompileException('icms.text macro currently doesn\'t support n:icms.text form, you may only use n:inner-icms.text');
        }
        if ($this->inMacroText) {
            throw new \LogicException('icms-text macros cannot be nested.');
        }
        $this->inMacroText = TRUE;
        $output = 'echo \'<div data-icms="container" class="' . self::EDITABLE_TEXT_CONTAINER_CLASS . '">\';';
        $output .= 'echo \'<div class="' . self::EDITABLE_TEXT_CONTENT_CLASS . '" data-icms="content" data-icms-id="\' . %node.word . \'">\';$icmstext = $_icmsetr(%node.word); if ($icmstext !== NULL) { echo %modify( $icmstext ); } else {} unset ($icmstext);echo "</div>";';
        $output .= 'if($_icmsea(%node.word)===TRUE){echo  \'<button data-icms-button="edit" class="' . self::EDITABLE_TEXT_EDIT_BUTTON_CLASS . '">' . self::BUTTON_TEXT . '</button>\';}';

        return $writer->write($output);
    }

    public function macroTextEnd(MacroNode $node, PhpWriter $writer)
    {
        $this->inMacroText = FALSE;
        return $writer->write('echo "</div>"');
    }
}
