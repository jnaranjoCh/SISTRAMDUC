<?php

namespace PlanSeptenalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use AppBundle\Entity\Usuario;
use RegistroUnicoBundle\Entity\Departamento;

/**
 * @ORM\Entity
 * @ORM\Table(name="plan_septenal_colectivo")
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
     * @ORM\Column(type="integer")
     */
    private $fin;

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

    public function __construct($inicio, $fin, Usuario $creator, \DateTime $creation_deadline)
    {
        $inicio = (int) $inicio;
        $fin = (int) $fin;

        if (($fin - $inicio + 1) != 7) {
            throw new \Exception('El rango septenal debe ser de 7 años.', 10);
        }

        $this->inicio = $inicio;
        $this->fin = $fin;
        $this->status = 'En creación';
        $this->setCreator($creator);

        if ($creation_deadline < new \DateTime()) {
            throw new \Exception('Fecha de finalización de proceso de creación debe ser superior a fecha actual', 50);
        }

        $this->creation_deadline = $creation_deadline;

        $this->planes_septenales_individuales = new ArrayCollection();
    }

    public function addPlanSeptenalIndividual(PlanSeptenalIndividual $planSeptenalIndividual)
    {
        if ($planSeptenalIndividual->getInicio() != $this->inicio || $planSeptenalIndividual->getFin() != $this->fin) {
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
}
