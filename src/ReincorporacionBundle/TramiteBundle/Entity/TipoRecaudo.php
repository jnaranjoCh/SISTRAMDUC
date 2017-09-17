<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use TramiteBundle\Entity\Recaudo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tipo_recaudo")
 */

class TipoRecaudo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = null;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Recaudo", mappedBy="tipo_recaudo")
     */
    protected $recaudos;

    public function __construct()
    {
        $this->recaudos = new ArrayCollection();
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
     * @return TipoTramite
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
     * Add recaudo
     *
     * @param \TramiteBundle\Entity\Recaudo $recaudo
     *
     * @return TipoRecaudo
     */
    public function addRecaudo(Recaudo $recaudo)
    {
        $this->recaudos[] = $recaudo;
        $recaudo->setTipoRecaudo($this);
        return $this;
    }

    /**
     * Remove recaudo
     *
     * @param \TramiteBundle\Entity\Recaudo $recaudo
     */
    public function removeRecaudo(Recaudo $recaudo)
    {
        $this->recaudos->removeElement($recaudo);
    }

    /**
     * Get recaudos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecaudos()
    {
        return $this->recaudos;
    }
}