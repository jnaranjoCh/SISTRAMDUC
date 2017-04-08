<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Usuario;

/**
 * @ORM\Entity
 * @ORM\Table(name="transicion")
 */

class Transicion
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $doc_info;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $estado;

    /**
     * @ORM\OneToOne(targetEntity="Tramite", mappedBy="transicion")
     * @ORM\JoinColumn(name="tramite_id", referencedColumnName="id")
     */
    private $tramite;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Usuario", mappedBy="transicion")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fecha
     *
     * @param date $fecha
     *
     * @return Transicion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return date
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Transicion
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set doc_info
     *
     * @param string $doc_info
     *
     * @return Transicion
     */
    public function setDoc_info($doc_info)
    {
        $this->doc_info = $doc_info;

        return $this;
    }

    /**
     * Get doc_info
     *
     * @return string
     */
    public function getDoc_info()
    {
        return $this->doc_info;
    }
}