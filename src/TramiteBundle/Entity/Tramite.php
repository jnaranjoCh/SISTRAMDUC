<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use ComisionRemuneradaBundle\Entity\SolicitudComisionServicio;
use TramiteBundle\Entity\Transicion;
use TramiteBundle\Entity\Documento;
use AppBundle\Entity\Usuario;
use TramiteBundle\Entity\TransicionConsejo;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"tramite" = "Tramite",
 *                        "comision" = "ComisionRemuneradaBundle\Entity\SolicitudComisionServicio",
 *                        "jubilacion" = "JubilacionBundle\Entity\TramiteJubilacion"})
 */
class Tramite
{
    protected  $type = "tramite";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
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
    private $observacion = "hola";

    /**
     * @ORM\ManyToOne(targetEntity="TipoTramite", inversedBy="tramites")
     * @ORM\JoinColumn(name="tipo_tramite_id", referencedColumnName="id")
     */
    protected $tipo_tramite_id;

    /**
     * @ORM\OneToOne(targetEntity="Transicion", mappedBy="tramite", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected  $transicion;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario", inversedBy="tramite")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $usuario_id;

    /**
     * @ORM\ManyToOne(targetEntity="Documento", inversedBy="tramite_id")
     */
    protected $documento_id;

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
     * Set tipo_tramite_id
     *
     * @param \TramiteBundle\Entity\TipoTramite $tipoTramite_id
     *
     * @return Tramite
     */
    public function setTipoTramite(\TramiteBundle\Entity\TipoTramite $tipo_tramite_id = null)
    {
        $this->tipo_tramite_id = $tipo_tramite_id;

        return $this;
    }

    /**
     * Get tipo_tramite_id
     *
     * @return \TramiteBundle\Entity\TipoTramite
     */
    public function getTipoTramite()
    {
        return $this->tipo_tramite_id;
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
     * @return tramite
     */
    public function addRecaudo(Recaudo $recaudo)
    {
        $this->recaudos[] = $recaudo;
        $recaudo->setTramite($this);

        return $this;
    }
    
    public function removeRecaudo(Recaudo $recaudo)
    {
        $this->recaudos->removeElement($recaudo);
        $recaudo->setTramite(null);
    }
    
    public function removeAllRecaudos()
    {
        $this->recaudos->clear();
    }

    public function assignTo(Usuario $owner)
    {
        $this->usuario_id = $owner;
        $owner->ownTramite($this);

        return $this;
    }
    
    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function getTransicion()
    {
        return $this->transicion;
    }
    
    public function ownTransicion(Transicion $transicion)
    {
        $this->transicion = $transicion;
        return $this;
    }

    public function getDocumento()
    {
        return $this->documento_id;
    }

    public function ownDocumento(Documento $documento)
    {
        $this->documento_id = $documento;
        return $this;
    }

    public function __toString()
    {
        return sprintf($this->getUsuarioId().'('.$this->getTransicion().')'.'('.$this->getRecaudos().')'.'('.$this->getDocumento().')');
    }
}