<?php

namespace DescargaHorariaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Usuario", mappedBy="tipo_dedicacion_id", cascade={"persist"})
     */
    protected $usuario;


    public function __construct()
    {
        $this->usuario = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return TipoDedicacion
     */
    public function setDescription($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->name;
    }
}

