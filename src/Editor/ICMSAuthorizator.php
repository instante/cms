<?php
namespace Instante\CMS\Editor;


use Nette\Object;
use Nette\Security\User as NUser;

class ICMSAuthorizator extends Object implements IICMSAuthorizator
{

    /** @var User */
    private $user;


    public function __construct(NUser $user)
    {
        $this->user = $user;
    }


    /**
     * @param string $ident
     * @return boolean
     */
    public function canRead($ident)
    {
        return TRUE;
    }

    /**
     * @param string $ident
     * @return boolean
     */
    public function canWrite($ident)
    {
        return $this->user->isLoggedIn() ? $this->user->isAllowed('services') : FALSE;
    }
}