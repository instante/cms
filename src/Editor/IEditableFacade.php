<?php

namespace Instante\CMS\Editor;

interface IEditableFacade
{
    /**
     * @param string $ident
     * @param string $text
     * @return void
     */
    public function setText($ident, $text);

    /**
     * @param string $ident
     * @return string
     */
    public function getText($ident);
}