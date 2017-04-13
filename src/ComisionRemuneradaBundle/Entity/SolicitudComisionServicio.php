<?php

namespace ComisionRemuneradaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use TramiteBundle\Entity\Recaudo;

/**
 * SolicitudComisionServicio
 *
 * @ORM\Table(name="solicitud_comision_servicio")
 * @ORM\Entity(repositoryClass="ComisionRemuneradaBundle\Repository\SolicitudComisionServicioRepository")
 */
class SolicitudComisionServicio
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array
     * @Assert\Count(
     *     min="4",
     *     max="4",
     *     minMessage = "Debe tener todos los archivos",
     *     maxMessage = "Debe tener todos los archivos"
     * )
     * @ORM\OneToMany(targetEntity="\TramiteBundle\Entity\Recaudo", mappedBy="SolicitudComisionServicio", cascade={"persist", "remove"})
     */
    protected $recaudos;

    public function __construct()
    {
        $this->recaudos = new ArrayCollection(array(new recaudo("Oficio de Solicitud de la Comisión de Servicio por parte del Beneficiario")
        ,new recaudo("Fotocopia de cédula de Identidad"),new recaudo("Fotocopia del RIF"),
            new recaudo("Copia de la Designación del cargos en la Administración")
        ));
    }

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
     * Get recaudos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecaudos()
    {
        return $this->recaudos;
    }

    /**
     * Add recaudo
     *
     * @param \TramiteBundle\Entity\Recaudo $recaudo
     * @return SolicitudComisionServicio
     */
    public function addRecaudo( \TramiteBundle\Entity\Recaudo $recaudo)
    {
        $this->recaudos[] = $recaudo;
        $recaudo->setSolicitudComisionServicio($this);

        return $this;
    }

    /**
     * Remove recaudo
     *
     * @param \TramiteBundle\Entity\Recaudo $recaudo
     */
    public function removeCapitulo(\TramiteBundle\Entity\Recaudo $recaudo)
    {
        $this->recaudos->removeElement($recaudo);
        $recaudo->setSolicitudComisionServicio(null);
    }

    /**
     * Remove recaudos
     *
     */
    public function removeAllRecaudos()
    {
        $this->recaudos->clear();
    }

    public function __toString() {
        return sprintf($this->getId());
    }
}

