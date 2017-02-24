<?php

namespace PlanSeptenalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use PlanSeptenalBundle\Entity\ActualizacionTramitePlanSeptenal;
use PlanSeptenalBundle\ValueObject\MonthlyDateRange;

/**
 * @ORM\Entity
 * @ORM\Table(name="tramite_plan_septenal")
 */
class TramitePlanSeptenal
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $tipo;

    /**
     * @ORM\Column(type="monthly_date_range", length=17)
     */
    private $periodo;

    /**
     * @ORM\ManyToOne(targetEntity="PlanSeptenalIndividual", inversedBy="tramites")
     **/
    private $plan_septenal_individual;

    /**
     * @ORM\OneToMany(targetEntity="ActualizacionTramitePlanSeptenal", mappedBy="tramite")
     */
    private $actualizaciones_solicitadas;

    public function __construct(array $attributes = [])
    {
        if (isset($attributes['tipo'])) {
            $this->setTipo($attributes['tipo']);
        }
        if (isset($attributes['periodo'])) {
            $this->setPeriodo($attributes['periodo']);
        }

        $this->actualizaciones_solicitadas = new ArrayCollection();
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function setPeriodo(MonthlyDateRange $periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    public function getPeriodo()
    {
        return $this->periodo;
    }

    public function attachToPlanSeptenal($plan_septenal_individual)
    {
        $this->plan_septenal_individual = $plan_septenal_individual;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getActualizacionesSolicitadas()
    {
        return $this->actualizaciones_solicitadas;
    }

    public function addSolicitudActualizacion(ActualizacionTramitePlanSeptenal $actualizacion)
    {
        $this->actualizaciones_solicitadas[] = $actualizacion;
    }
}
