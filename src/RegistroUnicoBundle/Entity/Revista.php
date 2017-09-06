<?php

namespace RegistroUnicoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Revista")
 */
class Revista
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
    private $description;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $volumen;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $primeraUltimaPagina;
    
    public function __construct(){}
    
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
     * Set description
     *
     * @param string $description
     *
     * @return Revista
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
     /**
     * Set volumen
     *
     * @param string $volumen
     *
     * @return Revista
     */
    public function setVolumen($volumen)
    {
        $this->volumen = $volumen;

        return $this;
    }

    /**
     * Get volumen
     *
     * @return string
     */
    public function getVolumen()
    {
        return $this->volumen;
    }
    
    /**
     * Set primeraUltimaPagina
     *
     * @param string $primeraUltimaPagina
     *
     * @return Revista
     */
    public function setPrimeraUltimaPagina($primeraUltimaPagina)
    {
        $this->primeraUltimaPagina = $primeraUltimaPagina;

        return $this;
    }

    /**
     * Get primeraUltimaPagina
     *
     * @return string
     */
    public function getPrimeraUltimaPagina()
    {
        return $this->primeraUltimaPagina;
    }
}

