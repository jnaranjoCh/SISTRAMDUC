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
     * @ORM\ManyToOne(targetEntity="Tipo_tramite", inversedBy="tramites")
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
     * Set tipoTramite
     *
     * @param \TramiteBundle\Entity\Tipo_tramite $tipoTramite
     *
     * @return Tramite
     */
    public function setTipoTramite(\TramiteBundle\Entity\Tipo_tramite $tipoTramite = null)
    {
        $this->tipo_tramite = $tipoTramite;

        return $this;
    }

    /**
     * Get tipoTramite
     *
     * @return \TramiteBundle\Entity\Tipo_tramite
     */
    public function getTipoTramite()
    {
        return $this->tipo_tramite;
    }
}
