<?php

namespace Instante\CMS\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="icms_editable_text")
 */
final class EditableText
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    private $ident = NULL;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $text = NULL;

    /**
     * EditableText constructor.
     * @param string $ident
     */
    public function __construct($ident)
    {
        $this->ident = $ident;
    }

    /**
     * @return string
     */
    public function getIdent()
    {
        return $this->ident;
    }


    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}