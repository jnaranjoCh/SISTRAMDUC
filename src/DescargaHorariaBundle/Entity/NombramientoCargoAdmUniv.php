<?php

namespace DescargaHorariaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NombramientoCargoAdmUniv
 *
 * @ORM\Table(name="nombramiento_cargo_adm_univ")
 * @ORM\Entity(repositoryClass="DescargaHorariaBundle\Repository\NombramientoCargoAdmUnivRepository")
 */
class NombramientoCargoAdmUniv
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nombramiento", type="datetime")
     */
    private $fechaNombramiento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin_nombramiento", type="datetime")
     */
    private $fechaFinNombramiento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_renuncia", type="datetime", nullable=true)
     */
    private $fechaRenuncia;

    /**
     * @var string
     *
     * @ORM\Column(name="isActive", type="string", length=2, options={"fixed" = true})
     */
    private $isActive;
    
    /**
     * @ORM\ManyToOne(targetEntity="CargoDesignacion", inversedBy="cargo_nomb")
     * @ORM\JoinColumn(name="cargo_designacion_id", referencedColumnName="id")
     */
    protected $cargo_designacion_id;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario", inversedBy="nombramiento_cargo")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $usuario_id;

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
     * Set fechaNombramiento
     *
     * @param \DateTime $fechaNombramiento
     *
     * @return NombramientoCargoAdmUniv
     */
    public function setFechaNombramiento($fechaNombramiento)
    {
        $this->fechaNombramiento = $fechaNombramiento;

        return $this;
    }

    /**
     * Get fechaNombramiento
     *
     * @return \DateTime
     */
    public function getFechaNombramiento()
    {
        return $this->fechaNombramiento;
    }

    /**
     * Set fechaFinNombramiento
     *
     * @param \DateTime $fechaFinNombramiento
     *
     * @return NombramientoCargoAdmUniv
     */
    public function setFechaFinNombramiento($fechaFinNombramiento)
    {
        $this->fechaFinNombramiento = $fechaFinNombramiento;

        return $this;
    }

    /**
     * Get fechaFinNombramiento
     *
     * @return \DateTime
     */
    public function getFechaFinNombramiento()
    {
        return $this->fechaFinNombramiento;
    }

    /**
     * Set fechaRenuncia
     *
     * @param \DateTime $fechaRenuncia
     *
     * @return NombramientoCargoAdmUniv
     */
    public function setFechaRenuncia($fechaRenuncia)
    {
        $this->fechaRenuncia = $fechaRenuncia;

        return $this;
    }

    /**
     * Get fechaRenuncia
     *
     * @return \DateTime
     */
    public function getFechaRenuncia()
    {
        return $this->fechaRenuncia;
    }

    /**
     * Set isActive
     *
     * @param string $isActive
     *
     * @return NombramientoCargoAdmUniv
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return string
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
}

