<?php

namespace Instante\CMS\Editor;

use Instante\CMS\Model\EditableText;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;

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

    /**
     * EditableFacade constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->editableTextRepository = $this->em->getRepository(EditableText::class);
    }

    public function setText($ident, $text)
    {
        $et = $this->editableTextRepository->find($ident);
        if ($et === NULL) {
            $et = new EditableText($ident);
            $this->em->persist($et);
        }
        $et->setText($text);
        $this->em->flush();
    }

    public function getText($ident)
    {
        $et = $this->editableTextRepository->find($ident);
        if ($et === NULL) {
            return NULL;
        } else {
            return $et->getText();
        }
    }
}
