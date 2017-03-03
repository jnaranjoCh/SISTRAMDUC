<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Rol")
 */
class Rol
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
    private $nombre;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="rol")
     */
    protected $usuarios;
    /**
     * @ManyToMany(targetEntity="Permisos", inversedBy="roles")
     * @JoinTable(name="permisos_roles",
     *      joinColumns={@JoinColumn(name="rol_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="permisos_id", referencedColumnName="id")}
     *      )
     */
    protected $permisos;
    
    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
        $this->permisos = new ArrayCollection();
    }
    /**
     * Get id
     *no
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Rol
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Rol
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

