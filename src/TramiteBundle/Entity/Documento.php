<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use TramiteBundle\Entity\Tramite;

/**
 * @ORM\Entity
 * @ORM\Table(name="documento")
 */
class Documento
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $asunto;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $acta;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $num;

    /**
     * @ORM\Column(type="text")
     */
    private $contenido;

    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    //private $codBarra;

    /**
     * @ORM\OneToOne(targetEntity="Tramite", inversedBy="documento_id")
     * @ORM\JoinColumn(name="tramite_id", referencedColumnName="id")
     */
    protected $tramite_id;

    /**
     * @ORM\OneToOne(targetEntity="TipoDocumento", inversedBy="documento")
     * @ORM\JoinColumn(name="tipo_documento_id", referencedColumnName="id")
     */
    protected $tipo_documento_id;

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
     * Set asunto
     *
     * @param string $asunto
     *
     * @return Documento
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get asunto
     *
     * @return string
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set acta
     *
     * @param string $acta
     *
     * @return Documento
     */
    public function setActa($acta)
    {
        $this->acta = $acta;

        return $this;
    }

    /**
     * Get acta
     *
     * @return string
     */
    public function getActa()
    {
        return $this->acta;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Documento
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set num
     *
     * @param string $num
     *
     * @return Documento
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get num
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set contenido
     *
     * @param text $contenido
     *
     * @return Documento
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return text
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set codBarra
     *
     * @param integer $codBarra
     *
     * @return Documento
     */
    public function setCodBarra($codBarra)
    {
        $this->codBarra = $codBarra;

        return $this;
    }

    /**
     * Get codBarra
     *
     * @return integer
     */
    public function getCodBarra()
    {
        return $this->codBarra;
    }

    public function getTramite_id()
    {
        return $this->tramite_id;
    }

    public function asignarDocA(Tramite $tramite)
    {
        $this->tramite_id = $tramite;
        $tramite->ownDocumento($this);

        return $this;
    }

    public function __toString()
    {
        return sprintf($this->getId());
    }

    /**
     * Set tipo_documento_id
     *
     * @param \TramiteBundle\Entity\TipoDocumento $tipoDocumento_id
     *
     * @return Documento
     */
    public function setTipoTramite(\TramiteBundle\Entity\TipoDocumento $tipo_documento_id = null)
    {
        $this->tipo_documento_id = $tipo_documento_id;

        return $this;
    }

    /**
     * Get $tipo_documento_id
     *
     * @return \TramiteBundle\Entity\TipoDocumento
     */
    public function getTipoTramite()
    {
        return $this->tipo_documento_id;
    }
}
