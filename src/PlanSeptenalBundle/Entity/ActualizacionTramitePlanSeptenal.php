<?php

namespace PlanSeptenalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="datetime")
     */
    private $mes_inicial;

    /**
     * @ORM\Column(type="datetime")
     */
    private $mes_final;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $motivo;

    /**
     * ORM\OneToOne(targetEntity="TramitePlanSeptenal", inversedBy="actualizaciones_solicitadas")
     **/
    private $tramite;

    public function setTramite(TramitePlanSeptenal $tramite)
    {
        $this->tramite = $tramite;
        $tramite->addSolicitudActualizacion($this);

        return $this;
    }

    public function setNuevoRango($mes_inicial, $mes_final)
    {
        $this->setMesInicial($mes_inicial);
        $this->setMesFinal($mes_final);

        return $this;
    }

    public function setMesInicial($mes_inicial)
    {
        $mes_inicial = \DateTime::createFromFormat('d/m/Y', '01/'.$mes_inicial)
            ->setTime(0, 0, 0);

        if (! is_null($this->mes_final)) {
            $this->checkRangeValidity($mes_inicial, $this->mes_final);
        }

        $this->mes_inicial = $mes_inicial;

        return $this;
    }

    public function setMesFinal($mes_final)
    {
        $mes_final = \DateTime::createFromFormat('m/Y', $mes_final)
            ->modify('last day of this month')
            ->setTime(23, 59, 59);

        if (! is_null($this->mes_inicial)) {
            $this->checkRangeValidity($this->mes_inicial, $mes_final);
        }

        $this->mes_final = $mes_final;

        return $this;
    }

    private function checkRangeValidity($mes_inicial, $mes_final)
    {
        if ($mes_inicial > $mes_final) {
            throw new \Exception('El mes inicial debe ser menor al mes final', 100);
        }
    }

    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    public function aplicarCambios()
    {
        $this->tramite->clearDateRange();
        $this->tramite->setMesInicial($this->mes_inicial->format('m/Y'));
        $this->tramite->setMesFinal($this->mes_final->format('m/Y'));

        return $this;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getMesInicial()
    {
        return $this->mes_inicial;
    }

    public function getMesFinal()
    {
        return $this->mes_final;
    }
}
