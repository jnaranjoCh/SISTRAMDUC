<?php

namespace PlanSeptenalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use AppBundle\Entity\Usuario;
use RegistroUnicoBundle\Entity\Departamento;

/**
 * @ORM\Entity
 * @ORM\Table(name="plan_septenal_colectvo", uniqueConstraints={@ORM\UniqueConstraint(name="one_plan_per_department", columns={"departamento_id", "inicio"})})
 */
class PlanSeptenalColectivo
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $inicio;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity="RegistroUnicoBundle\Entity\Departamento")
     * @ORM\JoinColumn(name="departamento_id", referencedColumnName="id")
     */
    private $departamento;

    /**
      * @ORM\Column(type="datetime", nullable=true)
      *
      */
    private $creation_deadline;

    /**
     * @ORM\OneToMany(targetEntity="PlanSeptenalIndividual", mappedBy="plan_septenal_colectivo")
     **/
    private $planes_septenales_individuales;

    public function __construct(int $inicio, Usuario $creator, \DateTime $creation_deadline)
    {
        $this->inicio = $inicio;
        $this->status = 'En creación';
        $this->setCreator($creator);

        $now = new \DateTime();
        if ($creation_deadline < $now) {
            throw new \Exception('Fecha de finalización de proceso de creación debe ser superior a fecha actual', 50);
        }

        $this->creation_deadline = $creation_deadline;

        $this->planes_septenales_individuales = new ArrayCollection();
    }

    public function addPlanSeptenalIndividual(PlanSeptenalIndividual $planSeptenalIndividual)
    {
        if ($planSeptenalIndividual->getInicio() != $this->inicio || $planSeptenalIndividual->getFin() != $this->getFin()) {
            throw new \Exception('Los rangos septenales no coinciden', 20);
        }

        $this->planes_septenales_individuales[] = $planSeptenalIndividual;
    }

    public function getPlanesSeptenalesIndividuales()
    {
        return $this->planes_septenales_individuales;
    }

    public function setCreator(Usuario $user)
    {
        if ( is_null($user->getDepartamento()) ) {
            throw new \Exception('El usuario creador debe pertenecer a un departamento', 40);
        }
        $this->creator = $user;
        $this->departamento = $user->getDepartamento();

        return $this;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDepartamento()
    {
        return $this->departamento;
    }

    public function getInicio()
    {
        return $this->inicio;
    }

    public function getFin()
    {
        return $this->inicio + 6;
    }

    public function toArray()
    {
        $planes = [];
        foreach ($this->planes_septenales_individuales as $plan) {
            $planes[] = $plan->toArray();
        }

        return [
            'inicio' => $this->getInicio(),
            'status' => $this->getStatus(),
            'planes' => $planes
        ];
    }
}
