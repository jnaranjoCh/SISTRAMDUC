<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipo_recaudo
 *
 * @ORM\Table(name="tipo_recaudo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Tipo_recaudoRepository")
 */
class Tipo_recaudo
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
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

   /**
     * @ORM\OneToMany(targetEntity="Recaudo", mappedBy="tipo_recaudo")
     */
    protected $recaudo;
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Tipo_recaudo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }
}

