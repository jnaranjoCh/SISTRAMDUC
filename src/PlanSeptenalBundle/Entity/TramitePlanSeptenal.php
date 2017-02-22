<?php

namespace PlanSeptenalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="datetime")
     */
    private $mes_inicial;

    /**
     * @ORM\Column(type="datetime")
     */
    private $mes_final;

    /**
     * @ORM\ManyToOne(targetEntity="PlanSeptenalIndividual", inversedBy="tramites")
     **/
    private $plan_septenal_individual;

    public function __construct(array $attributes = [])
    {
        if (isset($attributes['tipo'])) {
            $this->setTipo($attributes['tipo']);
        }
        if (isset($attributes['mes_inicial'])) {
            $this->setMesInicial($attributes['mes_inicial']);
        }
        if (isset($attributes['mes_final'])) {
            $this->setMesFinal($attributes['mes_final']);
        }
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

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

    public function getMesInicial()
    {
        return $this->mes_inicial;
    }

    public function getMesFinal()
    {
        return $this->mes_final;
    }

    public function attachToPlanSeptenal($plan_septenal_individual)
    {
        $this->plan_septenal_individual = $plan_septenal_individual;
    }

    public function getTipo()
    {
        return $this->tipo;
    }
}
