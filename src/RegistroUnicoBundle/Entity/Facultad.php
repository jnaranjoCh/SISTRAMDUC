<?php

namespace RegistroUnicoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Facultad")
 */
class Facultad
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

    protected $usuarios;
    
    /**
     * @ORM\OneToMany(targetEntity="Escuela", mappedBy="facultad")
     */
    protected $escuelas;
    
    /**
     * @ORM\OneToMany(targetEntity="Departamento", mappedBy="facultad")
     */
    protected $departamentos;
    
    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
        $this->escuelas = new ArrayCollection();
        $this->departamentos = new ArrayCollection();
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
     * Set description
     *
     * @param string $description
     *
     * @return Facultad
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
}

