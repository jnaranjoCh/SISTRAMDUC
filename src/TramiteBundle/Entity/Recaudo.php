<?php
/**
 * Created by PhpStorm.
 * User: Yulbais Seijas
 * Date: 11/03/2017
 * Time: 20:54
 */

namespace RecaudoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="recaudo")
 */
class Recaudo
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $documento_url;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_vencimiento;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoRecaudo")
     * @ORM\JoinColumn(name="tipo_recaudo_id", referencedColumnName="id")
     */
    protected $tipo_recaudo_id;

    
    /* private $dueno;

    private $dueno_id;

    private $dueno_tabla; */
    

    /**
     * Get id
     *
     * @return integer
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
        $this->documento_url = $documentoUrl;

        return $this;
    }

    /**
     * Get documentoUrl
     *
     * @return string
     */
    public function getDocumentoUrl()
    {
        return $this->documento_url;
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
        $this->fecha_vencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fecha_vencimiento;
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
        $this->tipo_recaudo_id = $tipoRecaudoId;

        return $this;
    }

    /**
     * Get tipoRecaudoId
     *
     * @return integer
     */
    public function getTipoRecaudoId()
    {
        return $this->tipo_recaudo_id;
    }

}
