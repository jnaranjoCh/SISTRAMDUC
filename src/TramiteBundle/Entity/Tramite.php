<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use TramiteBundle\Entity\Recaudo;

/**
 * Tramite
 *
 * @ORM\Table(name="tramite")
 * @ORM\Entity(repositoryClass="MyBundle\Repository\TramiteRepository")
 */
class Tramite
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
     *      min = "1",
     *      max = "10",
     *      minMessage = "Debe tener al menos 1 Archivo, en caso de ser el tomo completo",
     *      maxMessage = "Sólo puede tener como maximo {{ limit }} Archivos"
     * )
     * @ORM\OneToMany(targetEntity="Recaudo", mappedBy="tramite", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid
     */
    protected $recaudos;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $observacion;

    /**
     * @ORM\ManyToOne(targetEntity="TipoTramite", inversedBy="tramites")
     * @ORM\JoinColumn(name="tipo_tramite_id", referencedColumnName="id")
     */
    protected $tipo_tramite;

    public function __construct()
    {
        $this->recaudos = new ArrayCollection(array(new Recaudo("Recaudo_1")
        ,new Recaudo("Recaudo_2"),new Recaudo("Recaudo_3"),
            new Recaudo("Recaudo_4"),new Recaudo("Recaudoo_5")
        ));
    }
    
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return Tramite
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set tipo_tramite
     *
     * @param \TramiteBundle\Entity\TipoTramite $tipoTramite
     *
     * @return Tramite
     */
    public function setTipoTramite(\TramiteBundle\Entity\TipoTramite $tipo_tramite = null)
    {
        $this->tipo_tramite = $tipo_tramite;

        return $this;
    }

    /**
     * Get tipo_tramite
     *
     * @return \TramiteBundle\Entity\TipoTramite
     */
    public function getTipoTramite()
    {
        return $this->tipo_tramite;
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
     * @return tramite
     */
    public function addRecaudo(\TramiteBundle\Entity\Recaudo $recaudo)
    {
        $this->recaudos[] = $recaudo;
        $recaudo->setTramite($this);

        return $this;
    }
    /**
     * Remove recaudo
     *
     * @param \TramiteBundle\Entity\Recaudo $recaudo
     */
    public function removeRecaudo(\TramiteBundle\Entity\Recaudo $recaudo)
    {
        $this->recaudos->removeElement($recaudo);
        $recaudo->setTramite(null);
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