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
     * @ManyToMany(targetEntity="AppBundle\Entity\Usuario", inversedBy="cargos")
     * @JoinTable(name="usuarios_cargos",
     *      joinColumns={@JoinColumn(name="cargo_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="usuario_id", referencedColumnName="id")}
     *      )
     */
    protected $usuarios;
    
    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
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
}
