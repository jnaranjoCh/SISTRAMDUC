<?php

namespace PlanSeptenalBundle\Entity;

use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\OneToMany(targetEntity="PlanSeptenalIndividual", mappedBy="plan_septenal_colectivo")
     **/
    private $planes_septenales_individuales;

    public function __construct($inicio, $fin)
    {
        $inicio = (int) $inicio;
        $fin = (int) $fin;

        if (($fin - $inicio + 1) != 7) {
            throw new \Exception('El rango septenal debe ser de 7 años.', 10);
        }

        $this->inicio = $inicio;
        $this->fin = $fin;

        $this->planes_septenales_individuales = new ArrayCollection();
    }

    public function addPlanSeptenalIndividual(PlanSeptenalIndividual $planSeptenalIndividual)
    {
        if ($planSeptenalIndividual->getInicio() != $this->inicio || $planSeptenalIndividual->getFin() != $this->fin) {
            throw new \Exception('Los rangos septenales no coinciden');
        }

        $this->planes_septenales_individuales[] = $planSeptenalIndividual;
    }

    public function getPlanesSeptenalesIndividuales()
    {
        return $this->planes_septenales_individuales;
    }
}
