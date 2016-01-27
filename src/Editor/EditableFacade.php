<?php

namespace Instante\CMS\Editor;

use Instante\CMS\Model\EditableText;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Security\AuthenticationException;

/**
 * Service for storing/retrieving user editable texts.
 * @todo integration tests
 * @package Instante\CMS\Editor
 */
class EditableFacade implements IEditableFacade
{
    /** @var EntityManager */
    private $em;

    /** @var EntityRepository */
    private $editableTextRepository;

    /** @var IICMSAuthorizator */
    private $authorizator;

    /**
     * EditableFacade constructor.
     * @param EntityManager $em
     * @param IICMSAuthorizator $authorizator
     */
    public function __construct(EntityManager $em, IICMSAuthorizator $authorizator)
    {
        $this->em = $em;
        $this->editableTextRepository = $this->em->getRepository(EditableText::class);
        $this->authorizator = $authorizator;
    }

    public function setText($ident, $text)
    {
        if ($this->authorizator->canWrite($ident)) {
            $et = $this->editableTextRepository->find($ident);
            $action = "saved";
            if ($et === NULL) {
                $et = new EditableText($ident);
                $this->em->persist($et);
                $action = "created";
            }
            $et->setText($text);
            $this->em->flush();
            return ['action' => $action];
        } else {
            throw new AuthenticationException("You have no authorization to edit text");
        }
    }

    public function getText($ident)
    {
        if ($this->authorizator->canRead($ident)) {
            $et = $this->editableTextRepository->find($ident);
            if ($et === NULL) {
                return NULL;
            } else {
                return $et->getText();
            }
        } else {
            throw new AuthenticationException("You have no authorization to get text");
        }
    }
}
