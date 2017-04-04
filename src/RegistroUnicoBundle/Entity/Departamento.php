<?php

namespace RegistroUnicoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Departamento")
 */
class Departamento
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
     * @ORM\ManyToOne(targetEntity="Facultad", inversedBy="departamentos")
     * @ORM\JoinColumn(name="facultad_id", referencedColumnName="id")
     */
    protected $facultad;

    /**
     * @ORM\ManyToMany(targetEntity="Catedra")
     * @ORM\JoinTable(name="departamentos_catedras",
     *      joinColumns={@ORM\JoinColumn(name="departamento_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="catedra_id", referencedColumnName="id")}
     *      )
     */
    protected $catedras;

    public function __construct()
    {
        $this->catedras = new ArrayCollection();
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
     * @return Departamento
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
     * Set facultadId
     *
     * @param integer $facultadId
     *
     * @return Departamento
     */
    public function setFacultadId($facultadId)
    {
        $this->facultadId = $facultadId;

        return $this;
    }

    /**
     * Get facultadId
     *
     * @return int
     */
    public function getFacultadId()
    {
        return $this->facultadId;
    }
}
