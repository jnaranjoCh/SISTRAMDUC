<?php

namespace PlanSeptenalBundle\Entity;

use PlanSeptenalBundle\Entity\TramitePlanSeptenal;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="plan_septenal_individual")
 */
class PlanSeptenalIndividual
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
     * @ORM\OneToMany(targetEntity="TramitePlanSeptenal", mappedBy="plan_septenal_individual", cascade={"persist"})
     **/
    private $tramites;

    public function __construct($inicio, $fin)
    {
        $inicio = (int) $inicio;
        $fin = (int) $fin;

        if (($fin - $inicio + 1) != 7) {
            throw new \Exception('El rango septenal debe ser de 7 años.', 10);
        }

        $this->inicio = $inicio;
        $this->fin = $fin;
        $this->tramites = new ArrayCollection();
    }

    public function addTramite(TramitePlanSeptenal $new_tramite)
    {
        $new_tramite->attachToPlanSeptenal($this);

        $this->checkTramiteRange($new_tramite);

        $this->tramites[] = $new_tramite;
    }

    private function checkTramiteRange(TramitePlanSeptenal $new_tramite)
    {
        $this->checkTramiteIsWithinSeptenalRange($new_tramite);
        $this->checkTramitesDisjointness($new_tramite);
    }

    private function checkTramiteIsWithinSeptenalRange(TramitePlanSeptenal $new_tramite)
    {
        $año_inicial_tramite = (int) $new_tramite->getMesInicial()->format('Y');
        $año_final_tramite = (int) $new_tramite->getMesFinal()->format('Y');

        if ($año_inicial_tramite < $this->inicio || $año_final_tramite > $this->fin) {
            throw new \Exception('El Tramite debe estar dentro del periodo septenal.', 20);
        }
    }

    private function checkTramitesDisjointness(TramitePlanSeptenal $new_tramite)
    {
        foreach ($this->tramites as $tramite) {
            $disjoint = (
                $tramite->getMesFinal() < $new_tramite->getMesInicial() ||
                $tramite->getMesInicial() > $new_tramite->getMesFinal()
            );

            if (! $disjoint) {
                throw new \Exception('Los rangos de los tramites deben ser disjuntos.', 30);
            }
        }
    }

    public function getTramites()
    {
        return $this->tramites;
    }
}
