<?php

namespace ConcursosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resultado
 *
 * @ORM\Table(name="resultado")
 * @ORM\Entity(repositoryClass="ConcursosBundle\Repository\ResultadoRepository")
 */
class Resultado
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
     * @ORM\Column(name="cedulaAspirante", type="string", length=25)
     */
    private $cedulaAspirante;

    /**
     * @var int
     *
     * @ORM\Column(name="idConcurso", type="integer")
     */
    private $idConcurso;

    /**
     * @var int
     *
     * @ORM\Column(name="nroPrueba", type="integer", nullable=true)
     */
    private $nroPrueba;

    /**
     * @var int
     *
     * @ORM\Column(name="nota", type="integer")
     */
    private $nota;

    /**
     * @var int
     *
     * @ORM\Column(name="notaOral", type="integer", nullable=true)
     */
    private $notaOral;

    /**
     * @var int
     *
     * @ORM\Column(name="notaEscrito", type="integer", nullable=true)
     */
    private $notaEscrito;
    
    /**
     * @var int
     *
     * @ORM\Column(name="aptitud", type="integer", nullable=true)
     */
    private $aptitud;
    
    /**
     * @var int
     *
     * @ORM\Column(name="psicologica", type="integer", nullable=true)
     */
    private $psicologica;
    
    /**
     * @var int
     *
     * @ORM\Column(name="responsable", type="integer", nullable=true)
     */
    private $responsable;

    /**
     * @var string
     *
     * @ORM\Column(name="resultado", type="string", length=50)
     */
    private $resultado;

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
     * Set cedulaAspirante
     *
     * @param string $cedulaAspirante
     *
     * 
     * @return Resultado
     */
    public function setCedulaAspirante($cedulaAspirante)
    {
        $this->cedulaAspirante = $cedulaAspirante;

        return $this;
    }

    /**
     * Get cedulaAspirante
     *
     * @return string
     */
    public function getCedulaAspirante()
    {
        return $this->cedulaAspirante;
    }

    /**
     * Set idConcurso
     *
     * @param integer $idConcurso
     *
     * @return Resultado
     */
    public function setIdConcurso($idConcurso)
    {
        $this->idConcurso = $idConcurso;

        return $this;
    }

    /**
     * Get idConcurso
     *
     * @return int
     */
    public function getIdConcurso()
    {
        return $this->idConcurso;
    }
    
    /**
     * Set responsable
     *
     * @param integer $responsable
     *
     * @return Resultado
     */
    public function setResponsable($responsable)
    {
    	$this->responsable = $responsable;
    
    	return $this;
    }
    
    /**
     * Get responsable
     *
     * @return int
     */
    public function getResponsable()
    {
    	return $this->responsable;
    }

    /**
     * Set nroPrueba
     *
     * @param integer $nroPrueba
     *
     * @return Resultado
     */
    public function setNroPrueba($nroPrueba)
    {
        $this->nroPrueba = $nroPrueba;

        return $this;
    }

    /**
     * Get nroPrueba
     *
     * @return int
     */
    public function getNroPrueba()
    {
        return $this->nroPrueba;
    }

    /**
     * Set nota
     *
     * @param integer $nota
     *
     * @return Resultado
     */
    public function setNota($nota)
    {
        $this->nota = $nota;

        return $this;
    }

    /**
     * Get nota
     *
     * @return int
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * Set notaOral
     *
     * @param integer $notaOral
     *
     * @return Resultado
     */
    public function setNotaOral($notaOral)
    {
        $this->notaOral = $notaOral;

        return $this;
    }

    /**
     * Get notaOral
     *
     * @return int
     */
    public function getNotaOral()
    {
        return $this->notaOral;
    }
    
    /**
     * Set aptitud
     *
     * @param integer $aptitud
     *
     * @return Resultado
     */
    public function setAptitud($aptitud)
    {
    	$this->aptitud = $aptitud;
    
    	return $this;
    }
    
    /**
     * Get aptitud
     *
     * @return int
     */
    public function getAptitud()
    {
    	return $this->aptitud;
    }
    
    /**
     * Set psicologica
     *
     * @param integer $psicologica
     *
     * @return Resultado
     */
    public function setPsicologica($psicologica)
    {
    	$this->psicologica = $psicologica;
    
    	return $this;
    }
    
    /**
     * Get psicologica
     *
     * @return int
     */
    public function getPsicologica()
    {
    	return $this->psicologica;
    }

    /**
     * Set notaEscrito
     *
     * @param integer $notaEscrito
     *
     * @return Resultado
     */
    public function setNotaEscrito($notaEscrito)
    {
        $this->notaEscrito = $notaEscrito;

        return $this;
    }

    /**
     * Get notaEscrito
     *
     * @return int
     */
    public function getNotaEscrito()
    {
        return $this->notaEscrito;
    }

    /**
     * Set resultado
     *
     * @param string $resultado
     *
     * @return Resultado
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;

        return $this;
    }

    /**
     * Get resultado
     *
     * @return string
     */
    public function getResultado()
    {
        return $this->resultado;
    }
}

