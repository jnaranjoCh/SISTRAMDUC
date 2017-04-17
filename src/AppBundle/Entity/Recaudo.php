<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recaudo
 *
 * @ORM\Table(name="recaudo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecaudoRepository")
 */
class Recaudo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="documento_url", type="string", length=350)
     */
    private $documentoUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_vencimiento", type="date")
     */
    private $fechaVencimiento;

    /**
     * @ORM\ManyToOne(targetEntity="Tipo_recaudo", inversedBy="recaudo")
     * @ORM\JoinColumn(name="tipo_recaudo_id", referencedColumnName="id")
     */
    protected $tipo_recaudo;
    /**
     * @var int
     *
     * @ORM\Column(name="tipo_recaudo_id", type="integer")
     */
    private $tipoRecaudoId;

    /**
     * @var string
     *
     * @ORM\Column(name="dueño", type="string", length=50)
     */
    private $dueño;

    /**
     * @var string
     *
     * @ORM\Column(name="dueño_tabla", type="string", length=50)
     */
    private $dueñoTabla;

    /**
     * @var int
     *
     * @ORM\Column(name="dueño_id", type="integer")
     */
    private $dueñoId;


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
     * Set documentoUrl
     *
     * @param string $documentoUrl
     *
     * @return Recaudo
     */
    public function setDocumentoUrl($documentoUrl)
    {
        $this->documentoUrl = $documentoUrl;

        return $this;
    }

    /**
     * Get documentoUrl
     *
     * @return string
     */
    public function getDocumentoUrl()
    {
        return $this->documentoUrl;
    }

    /**
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return Recaudo
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set tipoRecaudoId
     *
     * @param integer $tipoRecaudoId
     *
     * @return Recaudo
     */
    public function setTipoRecaudoId($tipoRecaudoId)
    {
        $this->tipoRecaudoId = $tipoRecaudoId;

        return $this;
    }

    /**
     * Get tipoRecaudoId
     *
     * @return int
     */
    public function getTipoRecaudoId()
    {
        return $this->tipoRecaudoId;
    }

    /**
     * Set dueño
     *
     * @param string $dueño
     *
     * @return Recaudo
     */
    public function setDueño($dueño)
    {
        $this->dueño = $dueño;

        return $this;
    }

    /**
     * Get dueño
     *
     * @return string
     */
    public function getDueño()
    {
        return $this->dueño;
    }

    /**
     * Set dueñoTabla
     *
     * @param string $dueñoTabla
     *
     * @return Recaudo
     */
    public function setDueñoTabla($dueñoTabla)
    {
        $this->dueñoTabla = $dueñoTabla;

        return $this;
    }

    /**
     * Get dueñoTabla
     *
     * @return string
     */
    public function getDueñoTabla()
    {
        return $this->dueñoTabla;
    }

    /**
     * Set dueñoId
     *
     * @param integer $dueñoId
     *
     * @return Recaudo
     */
    public function setDueñoId($dueñoId)
    {
        $this->dueñoId = $dueñoId;

        return $this;
    }

    /**
     * Get dueñoId
     *
     * @return int
     */
    public function getDueñoId()
    {
        return $this->dueñoId;
    }
}

