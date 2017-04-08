<?php

namespace ComisionRemuneradaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use TramiteBundle\Entity\Recaudo;
use TramiteBundle\Entity\Tramite;

/**
 * SolicitudComisionServicio
 *
 * @ORM\Table(name="solicitud_comision_servicio")
 * @ORM\Entity(repositoryClass="ComisionRemuneradaBundle\Repository\SolicitudComisionServicioRepository")
 */
class SolicitudComisionServicio
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
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
     * @ORM\OneToMany(targetEntity="\TramiteBundle\Entity\Recaudo", mappedBy="SolicitudComisionServicio", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $recaudos;

    /**
     * @ORM\OneToOne(targetEntity="\TramiteBundle\Entity\Tramite", mappedBy="SolicitudComisionServicio")
     * @ORM\JoinColumn(name="tramite_id", referencedColumnName="id")
     */
    protected $tramite;

    public function __construct()
    {
        $this->recaudos = new ArrayCollection(array(new recaudo("Recaudo_1")
        ,new recaudo("Recaudo_2"),new recaudo("Recaudo_3"),
            new recaudo("Recaudo_4")
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
}

