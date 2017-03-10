<?php

namespace ClausulasContractualesBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Hijo")
 */
class Hijo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cedulaMadre;

    /**
     * @ORM\Column(type="integer")
     */
    private $cedulaPadre;

    /**
     * @ORM\Column(type="integer")
     */
    private $cedulaHijo;

    /** 
      * @ORM\Column(type="datetime") 
      */
    private $fechaNacimiento;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $primerNombre;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $segundoNombre;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $primerApellido;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $segundoApellido;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $nacionalidadd;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $partidaNacimientoUrl;

    protected $usuarios;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
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
     * Set cedulaMadre
     *
     * @param integer $cedulaMadre
     *
     * @return Hijo
     */
    public function setCedulaMadre($cedulaMadre)
    {
        $this->cedulaMadre = $cedulaMadre;

        return $this;
    }

    /**
     * Get cedulaMadre
     *
     * @return int
     */
    public function getCedulaMadre()
    {
        return $this->cedulaMadre;
    }

    /**
     * Set cedulaPadre
     *
     * @param integer $cedulaPadre
     *
     * @return Hijo
     */
    public function setCedulaPadre($cedulaPadre)
    {
        $this->cedulaPadre = $cedulaPadre;

        return $this;
    }

    /**
     * Get cedulaPadre
     *
     * @return int
     */
    public function getCedulaPadre()
    {
        return $this->cedulaPadre;
    }

    /**
     * Set cedulaHijo
     *
     * @param integer $cedulaHijo
     *
     * @return Hijo
     */
    public function setCedulaHijo($cedulaHijo)
    {
        $this->cedulaHijo = $cedulaHijo;

        return $this;
    }

    /**
     * Get cedulaHijo
     *
     * @return int
     */
    public function getCedulaHijo()
    {
        return $this->cedulaHijo;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return Hijo
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set primerNombre
     *
     * @param string $primerNombre
     *
     * @return Hijo
     */
    public function setPrimerNombre($primerNombre)
    {
        $this->primerNombre = $primerNombre;

        return $this;
    }

    /**
     * Get primerNombre
     *
     * @return string
     */
    public function getPrimerNombre()
    {
        return $this->primerNombre;
    }

    /**
     * Set segundoNombre
     *
     * @param string $segundoNombre
     *
     * @return Hijo
     */
    public function setSegundoNombre($segundoNombre)
    {
        $this->segundoNombre = $segundoNombre;

        return $this;
    }

    /**
     * Get segundoNombre
     *
     * @return string
     */
    public function getSegundoNombre()
    {
        return $this->segundoNombre;
    }

    /**
     * Set primerApellido
     *
     * @param string $primerApellido
     *
     * @return Hijo
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    /**
     * Get primerApellido
     *
     * @return string
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * Set segundoApellido
     *
     * @param string $segundoApellido
     *
     * @return Hijo
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    /**
     * Get segundoApellido
     *
     * @return string
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * Set nacionalidadd
     *
     * @param string $nacionalidadd
     *
     * @return Hijo
     */
    public function setNacionalidadd($nacionalidadd)
    {
        $this->nacionalidadd = $nacionalidadd;

        return $this;
    }

    /**
     * Get nacionalidadd
     *
     * @return string
     */
    public function getNacionalidadd()
    {
        return $this->nacionalidadd;
    }

    /**
     * Set partidaNacimientoUrl
     *
     * @param string $partidaNacimientoUrl
     *
     * @return Hijo
     */
    public function setPartidaNacimientoUrl($partidaNacimientoUrl)
    {
        $this->partidaNacimientoUrl = $partidaNacimientoUrl;

        return $this;
    }

    /**
     * Get partidaNacimientoUrl
     *
     * @return string
     */
    public function getPartidaNacimientoUrl()
    {
        return $this->partidaNacimientoUrl;
    }
}

