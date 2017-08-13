<?php

namespace RegistroUnicoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use \stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="Registro")
 */
class Registro
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $año;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isValidate;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $institucionEmpresa;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $description;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoRegistro", inversedBy="registros")
     * @ORM\JoinColumn(name="tipo_registro_id", referencedColumnName="id")
     */
    protected $tipo_registro;
    
    /**
     * @ORM\ManyToOne(targetEntity="Nivel", inversedBy="registros")
     * @ORM\JoinColumn(name="nivel_id", referencedColumnName="id")
     */
    protected $nivel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Estatus", inversedBy="registros")
     * @ORM\JoinColumn(name="estatus_id", referencedColumnName="id")
     */
    protected $estatus;
    

    /**
     * @ORM\ManyToMany(targetEntity="Participante", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="registro_participantes",
     *      joinColumns={@ORM\JoinColumn(name="registro_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="participante_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $participantes;

    /**
     * @ORM\ManyToMany(targetEntity="Revista", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="registro_revistas",
     *      joinColumns={@ORM\JoinColumn(name="registro_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="revista_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $revistas;
    
    
    /**
     * @ORM\Column(type="integer")
     */
    private $tipoRegistroId;

    /**
     * @ORM\Column(type="integer")
     */
    private $nivelId;

    /**
     * @ORM\Column(type="integer")
     */
    private $estatusId;


    public function __construct()
    {
        $this->participantes = new ArrayCollection();
        $this->revistas = new ArrayCollection();
        $this->usuarios = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set año
     *
     * @param integer $año
     *
     * @return Registro
     */
    public function setAño($año)
    {
        $this->año = $año;

        return $this;
    }

    /**
     * Get año
     *
     * @return integer
     */
    public function getAño()
    {
        return $this->año;
    }

    /**
     * Set $institucionEmpresa
     *
     * @param string $institucionEmpresa
     *
     * @return Registro
     */
    public function setInstitucionEmpresa($institucionEmpresa)
    {
        $this->institucionEmpresa = $institucionEmpresa;

        return $this;
    }

    /**
     * Get $institucionEmpresa
     *
     * @return string
     */
    public function getInstitucionEmpresa()
    {
        return $this->institucionEmpresa;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Registro
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
     * Set nivelId
     *
     * @param integer $nivelId
     *
     * @return Registro
     */
    public function setNivelId($nivelId)
    {
        $this->nivelId = $nivelId;

        return $this;
    }

    /**
     * Get nivelId
     *
     * @return integer
     */
    public function getNivelId()
    {
        return $this->nivelId;
    }

    /**
     * Set estatusId
     *
     * @param integer $estatusId
     *
     * @return Registro
     */
    public function setEstatusId($estatusId)
    {
        $this->estatusId = $estatusId;

        return $this;
    }

    /**
     * Get estatusId
     *
     * @return integer
     */
    public function getEstatusId()
    {
        return $this->estatusId;
    }
    
    
    /**
     * Get tipo_registro
     *
     * @return TipoRegistro
     */
    public function getTipo()
    {
        return $this->tipo_registro;
    }

    /**
     * Set tipo_registro
     *
     * @param TipoRegistro $tipo_registro
     *
     * @return Registro
     */
    public function setTipo(TipoRegistro $tipo_registro)
    {
        $this->tipo_registro = $tipo_registro;

        return $this;
    }
    
    public function getTipoRegitroId()
    {
        return $this->tipoRegistroId;
    }

    /**
     * Set tipo_registro
     *
     * @param TipoRegistro $tipo_registro
     *
     * @return Registro
     */
    public function setTipoRegitroId($tipoRegistroId)
    {
        $this->tipoRegistroId = $tipoRegistroId;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return Nivel
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set nivel
     *
     * @param Nivel $nivel
     *
     * @return Registro
     */
    public function setNivel(Nivel $nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }
    
    /**
     * Get validado
     *
     * @return isValidate
     */
    public function getIsValidate()
    {
        return $this->isValidate;
    }

    /**
     * Set validado
     *
     * @param $validate
     *
     * @return Registro
     */
    public function setIsValidate($validate)
    {
        $this->isValidate = $validate;

        return $this;
    }
    
    /**
     * Get estatus
     *
     * @return integer
     */
    public function getEstatus()
    {
        return $this->estatus;
    }
    
    public function getRevistasAsObject()
    {
        return $this->revistas;
    }
    
    public function getParticipantesAsObject()
    {
        return $this->participantes;
    }

    /**
     * Set estatus
     *
     * @param Estatus $estatus
     *
     * @return Registro
     */
    public function setEstatus(Estatus $estatus)
    {
        $this->estatus = $estatus;

        return $this;
    }
    
     public function getParticipantes($select,&$k)
     {
        $participantes = new stdClass;
        $i = 0;
        $data[] = [];
        foreach($this->participantes->toArray() as $participante){
            $selectAux = str_replace('<select id="IdDelRegistro0" class="form-control select2" style="width: 240px;">','<select id="IdDelRegistro'.$k.'" class="form-control select2" style="width: 240px;">',$select);
            $data[$i]['IdDelRegistro'] = $selectAux;
            $data[$i]['Nombre'] = '<input id="Nombre'.$k.'" value="'.$participante->getNombre().'" type="text" class="form-control" placeholder="Nombre">';
            $data[$i]['Cedula'] = '<input id="Cedula'.$k.'" value="'.$participante->getCedula().'" type="number" class="form-control" placeholder="Cédula">';
            $i++;
            $k++;
        }

        $participantes->data = $data;
        $participantes->num = $i;
        return $participantes;
     }
    
     public function addParticipante($participante)
     {
        $this->participantes[] = $participante;
     }

     public function addParticipantes($participantes)
     {
         if($participantes != null){
            foreach($participantes as $participante)
                $this->addParticipante($participante);
         }
     }
     
     public function getRevistas($select,&$k)
     {
        $revistas = new stdClass;
        $i = 0;
        $data[] = [];
        foreach($this->revistas->toArray() as $revista){
            $selectAux = str_replace('<select id="IdDelRegistroRevista0" class="form-control select2" style="width: 240px;">','<select id="IdDelRegistroRevista'.$k.'" class="form-control select2" style="width: 240px;">',$select);            
            $data[$i]['IdDelRegistro'] = $selectAux;
            $data[$i]['Revista'] = '<input id="Revista'.$k.'" value="'.$revista->getDescription().'" type="text" class="form-control" placeholder="Revista">';
            $i++;
            $k++;
        }

        $revistas->data = $data;
        $revistas->num = $i;
        return $revistas;
     }
    
     public function addRevista($revista)
     {
        $this->revistas[] = $revista;
     }

     public function addRevistas($revistas)
     {
         if($revistas != null){
            foreach($revistas as $revista)
                $this->addRevista($revista);
         }
     }
     
    public function setRevistas($revistas)
    {
        $this->revistas = $revistas;
        return $this;
    }
    
    public function setParticipante($participante)
    {
        $this->participantes = $participante;
        return $this;
    }
}
