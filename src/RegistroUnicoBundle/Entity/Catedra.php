<?php

namespace RegistroUnicoBundle\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="Catedra")
 */
class Catedra
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
     * @ManyToMany(targetEntity="Escuela", inversedBy="catedras")
     * @JoinTable(name="escuelas_catedras",
     *      joinColumns={@JoinColumn(name="catedra_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="escuela_id", referencedColumnName="id")}
     *      )
     */
    protected $escuelas;

    /**
     * @ManyToMany(targetEntity="Departamento", inversedBy="catedras")
     * @JoinTable(name="departamentos_catedras",
     *      joinColumns={@JoinColumn(name="catedra_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="departamento_id", referencedColumnName="id")}
     *      )
     */
    protected $departamentos;
    
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
     * @return Catedra
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

