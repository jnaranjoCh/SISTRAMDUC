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

