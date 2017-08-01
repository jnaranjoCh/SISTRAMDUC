<?php

namespace RegistroUnicoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Cargo")
 */
class Cargo
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
     * @ORM\OneToMany(targetEntity="UsuarioFechaCargo", mappedBy="cargos", cascade={"persist", "remove"})
     */
    protected $UsuarioFechaCargos;
    
    public function __construct()
    {
         $this->UsuarioFechaCargos = new ArrayCollection();
    }
    
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
     * Set description
     *
     * @param string $description
     *
     * @return Cargo
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
    

    public function __toString()
    {
        return sprintf($this->getId());
    }

    public function getUsuarioFechaCargos()
    {
        return $this->UsuarioFechaCargos->toArray();
    }

    public function setUsuarioFechaCargos(UsuarioFechaCargo $UsuarioFechaCargos)
    {
        $this->UsuarioFechaCargos->set($this->UsuarioFechaCargos->indexOf($UsuarioFechaCargos),$UsuarioFechaCargos);
        return $this;
    }
    
    public function addUsuarioFechaCargos(UsuarioFechaCargo $UsuarioFechaCargo)
    {
        if (!$this->UsuarioFechaCargos->contains($UsuarioFechaCargo)) {
            $this->UsuarioFechaCargos->add($UsuarioFechaCargo);
            $UsuarioFechaCargo->setCargo($this);
        }

        return $this;
    }
}
