<?php

namespace ConcursoOposicionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use ConcursoOposicionBundle\Entity\Acta;

/**
 * Autorizadores
 *
 * @ORM\Table(name="autorizadores")
 * @ORM\Entity(repositoryClass="ConcursoOposicionBundle\Repository\AutorizadoresRepository")
 */
class Autorizadores
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
     * @ORM\Column(name="cargo", type="string", length=200)
     */
    private $cargo;

    /**
     * @var string
     *
     * @ORM\Column(name="cedula", type="string", length=30)
     */
    private $cedula;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreApellido", type="string", length=200, nullable=true)
     */
    private $nombreApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="firma", type="string", length=50, nullable=true)
     */
    private $firma;

    /**
     * @ORM\ManyToOne(targetEntity="ConcursoOposicionBundle\Entity\Acta", inversedBy="autorizadores")
     * @ORM\JoinColumn(name="acta_id", referencedColumnName="id")
     */
    protected $acta;


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
     * Set cargo
     *
     * @param string $cargo
     *
     * @return Autorizadores
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set cedula
     *
     * @param string $cedula
     *
     * @return Autorizadores
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return string
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set nombreApellido
     *
     * @param string $nombreApellido
     *
     * @return Autorizadores
     */
    public function setNombreApellido($nombreApellido)
    {
        $this->nombreApellido = $nombreApellido;

        return $this;
    }

    /**
     * Get nombreApellido
     *
     * @return string
     */
    public function getNombreApellido()
    {
        return $this->nombreApellido;
    }

    /**
     * Set firma
     *
     * @param string $firma
     *
     * @return Autorizadores
     */
    public function setFirma($firma)
    {
        $this->firma = $firma;

        return $this;
    }

    /**
     * Get firma
     *
     * @return string
     */
    public function getFirma()
    {
        return $this->firma;
    }

    /**
     * Set acta
     *
     * @param integer $acta
     *
     * @return Acta
     */
    public function setActa(Acta $acta = null)
    {
        $this->acta = $acta;

        return $this;
    }

    /**
     * Get acta
     *
     * @return int
     */
    public function getActa()
    {
        return $this->acta;
    }
}

