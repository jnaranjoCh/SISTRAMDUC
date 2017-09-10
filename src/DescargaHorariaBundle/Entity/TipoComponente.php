<?php

namespace DescargaHorariaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use DescargaHorariaBundle\Entity\PlanAcademicoIntegral;

/**
 * TipoComponente
 *
 * @ORM\Table(name="tipo_componente")
 * @ORM\Entity(repositoryClass="DescargaHorariaBundle\Repository\TipoComponenteRepository")
 */
class TipoComponente
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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;


    /**
     * @ORM\OneToMany(targetEntity="PlanAcademicoIntegral", mappedBy="tipo_componente_id")
     */
    protected $plan_tipo_comp;


    public function __construct()
    {
        $this->plan_tipo_comp = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return TipoComponente
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get plan_tipo_comp
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanTipoComp()
    {
       return $this->plan_tipo_comp;
    }
}

