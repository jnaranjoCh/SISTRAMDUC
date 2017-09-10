<?php

namespace DescargaHorariaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsuarioDedicacion
 *
 * @ORM\Table(name="usuario_dedicacion")
 * @ORM\Entity(repositoryClass="DescargaHorariaBundle\Repository\UsuarioDedicacionRepository")
 */
class UsuarioDedicacion
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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Usuario", inversedBy="dedicacion")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $usuario_id;
    
    /**
     * @ORM\OneToOne(targetEntity="TipoDedicacion", inversedBy="usuario_tipo_dedicacion")
     * @ORM\JoinColumn(name="tipo_dedicacion_id", referencedColumnName="id")
     */
    protected $tipo_dedicacion_id;
    
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

