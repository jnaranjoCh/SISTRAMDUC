<?php
/**
 * Created by PhpStorm.
 * User: Anyelys
 * Date: 04/03/2017
 * Time: 01:10 PM
 */

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tramite")
 */

class Tramite
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $observacion;

    /**
     * @ORM\ManyToOne(targetEntity="TipoTramite", inversedBy="tramites")
     * @ORM\JoinColumn(name="tipo_tramite_id", referencedColumnName="id")
     */
    protected $tipo_tramite;

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
}
