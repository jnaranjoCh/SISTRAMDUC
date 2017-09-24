<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Monto
 *
 * @ORM\Table(name="monto")
 * @ORM\Entity(repositoryClass="TramiteBundle\Repository\MontoRepository")
 */
class Monto
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
     * @ORM\Column(name="description", type="string", length=50)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=2, scale=0)
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="Monto", inversedBy="montos")
     * @ORM\JoinColumn(name="monto_id", referencedColumnName="id", onDelete="CASCADE")
     *
     */
    protected $tramite;

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
     * Set description
     *
     * @param string $description
     *
     * @return Monto
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Monto
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }


    /**
     * Set tramite
     *
     * @return monto
     */
     public function setTramite(Tramite $tramite = null)
     {
         $this->tramite = $tramite;
         return $this;
     }
 
     /**
      * Get tramite
      *
      * @return \TramiteBundle\Entity\Monto
      */
     public function getTramite()
     {
         return $this->tramite;
     }
}

