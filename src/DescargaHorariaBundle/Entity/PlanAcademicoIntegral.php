<?php

namespace DescargaHorariaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanAcademicoIntegral
 *
 * @ORM\Table(name="plan_academico_integral")
 * @ORM\Entity(repositoryClass="DescargaHorariaBundle\Repository\PlanAcademicoIntegralRepository")
 */
class PlanAcademicoIntegral

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
     * @var int
     *
     * @ORM\Column(name="horas", type="integer")
     */
    private $horas;

    /**
     * @ORM\ManyToOne(targetEntity="TipoComponente", inversedBy="plan_tipo_comp")
     * @ORM\JoinColumn(name="tipo_componente_id", referencedColumnName="id")
     */
    protected $tipo_componente_id;


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
     * Set horas
     *
     * @param integer $horas
     *
     * @return PlanAcademicoIntegral
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return int
     */
    public function getHoras()
    {
        return $this->horas;
    }
    
}

