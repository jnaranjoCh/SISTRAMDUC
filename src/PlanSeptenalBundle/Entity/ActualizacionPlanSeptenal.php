<?php

namespace PlanSeptenalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use PlanSeptenalBundle\Entity\ActualizacionTramitePlanSeptenal;

/**
 * @ORM\Entity
 * @ORM\Table(name="actualizacion_plan_septenal")
 */
class ActualizacionPlanSeptenal
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $estado;

    /**
     * @ORM\OneToMany(targetEntity="ActualizacionTramitePlanSeptenal", mappedBy="actualizacion_plan_septenal")
     **/
    private $actualizaciones_tramites;

    public function __construct()
    {
        $this->estado = 'Pendiente';
        $this->actualizaciones_tramites = new ArrayCollection();
    }

    public function addActualizacionTramite(ActualizacionTramitePlanSeptenal $actualizacion_tramite)
    {
        $this->actualizaciones_tramites[] = $actualizacion_tramite;

        return $this;
    }

    public function aprobar()
    {
        $this->estado = 'Aprobada';

        foreach ($this->actualizaciones_tramites as $actualizacion_tramite) {
            $actualizacion_tramite->aplicarCambios();
        }

        return $this;
    }

    public function getEstado()
    {
        return $this->estado;
    }
}
