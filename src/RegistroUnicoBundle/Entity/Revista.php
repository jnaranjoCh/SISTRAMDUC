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
     * @ManyToMany(targetEntity="Registro", inversedBy="revistas")
     * @JoinTable(name="registro_revistas",
     *      joinColumns={@JoinColumn(name="revista_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="registro_id", referencedColumnName="id")}
     *      )
     */
    protected $registros;
    
    public function __construct()
    {
        $this->registros = new ArrayCollection();
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
}

