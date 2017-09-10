<?php

namespace DescargaHorariaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoDedicacion
 *
 * @ORM\Table(name="tipo_dedicacion")
 * @ORM\Entity(repositoryClass="DescargaHorariaBundle\Repository\TipoDedicacionRepository")
 */
class TipoDedicacion
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;


    /**
     * @ORM\OneToOne(targetEntity="DescargaHorariaBundle\Entity\UsuarioDedicacion", mappedBy="tipo_dedicacion_id", cascade={"persist", "remove"})
     */
    protected  $usuario_tipo_dedicacion;
    
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
     * Set name
     *
     * @param string $name
     *
     * @return TipoDedicacion
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

