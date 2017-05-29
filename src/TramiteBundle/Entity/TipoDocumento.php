<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use TramiteBundle\Entity\Documento;

/**
 * @ORM\Entity
 * @ORM\Table(name="tipo_documento")
 */

class TipoDocumento
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
//* @ORM\GeneratedValue(strategy="AUTO")
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\OneToOne(targetEntity="Documento", mappedBy="tipo_documento_id")
     */
    protected $documento;

    public function __construct()
    {
        $this->documento = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set nombre
     *
     * @param string $nombre
     *
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add tramite
     *
     * @param \TramiteBundle\Entity\Tramite $tramite
     *
     * @return TipoDocumento
     */
    public function addDocumento(\TramiteBundle\Entity\Documento $documento)
    {
        $this->documento[] = $documento;

        return $this;
    }

    /**
     * Get documento
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumento()
    {
        return $this->documento;
    }
}