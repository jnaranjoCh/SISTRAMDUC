<?php

namespace PlanSeptenalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use PlanSeptenalBundle\ValueObject\MonthlyDateRange;

/**
 * @ORM\Entity
 * @ORM\Table(name="actualizacion_tramite_plan_septenal")
 */
class ActualizacionTramitePlanSeptenal
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="monthly_date_range")
     */
    private $nuevo_periodo;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $motivo;

    /**
     * @ORM\ManyToOne(targetEntity="TramitePlanSeptenal", inversedBy="actualizaciones_solicitadas")
     **/
    private $tramite;

    /**
     * @ORM\ManyToOne(targetEntity="ActualizacionPlanSeptenal", inversedBy="actualizaciones_tramites")
     */
    private $actualizacion_plan_septenal;

    public function setTramite(TramitePlanSeptenal $tramite)
    {
        $this->tramite = $tramite;
        $tramite->addSolicitudActualizacion($this);

        return $this;
    }

    public function setNuevoPeriodo(MonthlyDateRange $nuevo_periodo)
    {
        $this->nuevo_periodo = $nuevo_periodo;

        return $this;
    }

    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    public function aplicarCambios()
    {
        $this->tramite->setPeriodo($this->nuevo_periodo);

        return $this;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getNuevoPeriodo()
    {
        return $this->nuevo_periodo;
    }
}
