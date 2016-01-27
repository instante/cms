<?php

namespace Instante\CMS\Editor;

interface IICMSAuthorizator
{
    /**
     * @param string $ident
     * @return boolean
     */
    public function canRead($ident);

    /**
     * @param string $ident
     * @return boolean
     */
    public function canWrite($ident);
}