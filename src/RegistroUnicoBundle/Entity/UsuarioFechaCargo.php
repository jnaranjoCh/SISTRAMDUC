<?php

namespace RegistroUnicoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Usuario;

/**
 * UsuarioFechaCargo
 *
 * @ORM\Table(name="usuario_fecha_cargo")
 * @ORM\Entity(repositoryClass="RegistroUnicoBundle\Repository\UsuarioFechaCargoRepository")
 */
class UsuarioFechaCargo
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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    /**
<<<<<<< HEAD
     * @ORM\Column(type="boolean", nullable=true)
=======
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin_cargo", type="datetime", nullable=true)
     */
    private $fechaFinCargo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_renuncia", type="datetime", nullable=true)
     */
    private $fechaRenuncia;
    
    /**
     * @ORM\Column(type="boolean")
>>>>>>> 41f63c35c7301f9a356abdbda0d7c43c2b706f76
     */
    private $isValidate;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario", inversedBy="UsuarioFechaCargos")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $usuarios;

    /**
     * @ORM\ManyToOne(targetEntity="Cargo", inversedBy="UsuarioFechaCargos")
     * @ORM\JoinColumn(name="cargo_id", referencedColumnName="id")
     */
    protected $cargos;
    
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return UsuarioFechaCargo
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

     /**
     * Set fechaFinNombramiento
     *
     * @param \DateTime $fechaFinNombramiento
     *
     * @return UsuarioFechaCargo
     */
    public function setFechaFinCargo($fechaFinCargo)
    {
        $this->fechaFinCargo = $fechaFinCargo;

        return $this;
    }

    /**
     * Get fechaFinNombramiento
     *
     * @return \DateTime
     */
    public function getFechaFinCargo()
    {
        return $this->fechaFinCargo;
    }

    /**
     * Set fechaRenuncia
     *
     * @param \DateTime $fechaRenuncia
     *
     * @return UsuarioFechaCargo
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
     * Set isValidate
     *
     * @param \boolean $isValidate
     *
     * @return UsuarioFechaCargo
     */
    public function setIsValidate($isValidate)
    {
        $this->isValidate = $isValidate;

        return $this;
    }

    /**
     * Get isValidate
     *
     * @return  UsuarioFechaCargo
     */
    public function getIsValidate()
    {
        return $this->isValidate;
    }
    
    public function getUsuario()
    {
        return $this->usuarios;
    }

    public function setUsuario(Usuario $usuario = null)
    {
        $this->usuarios = $usuario;
        return $this;
    }

    public function getCargo()
    {
        return $this->cargos;
    }

    public function setCargo(Cargo $cargo = null)
    {
        $this->cargos = $cargo;
        return $this;
    }
    
    
}

