<?php

namespace TramiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use TramiteBundle\Entity\Tramite;
use TramiteBundle\Entity\TipoRecaudo;
use AppBundle\Entity\Usuario;

/**
 * Recaudo
 *
 * @ORM\Table(name="recaudo")
 * @ORM\Entity(repositoryClass="TramiteBundle\Repository\RecaudoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Recaudo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name = "";
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $valor;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_vencimiento;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $tabla = " ";

    /**
     * @var UploadedFile
     *
     * @Assert\File(
     *     maxSize = "20Mi",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Por favor cargar un PDF valido",
     * )
     * @Assert\NotNull(message="Por favor, cargar el Documento como un archivo PDF.")
     */
    private $file;

    private $temp;

    /**
     * @ORM\ManyToOne(targetEntity="Tramite", inversedBy="recaudos")
     * @ORM\JoinColumn(name="tramite_id", referencedColumnName="id", onDelete="CASCADE")
     *
     */
    protected $tramite;

    /**
     * @ORM\ManyToOne(targetEntity="TipoRecaudo", inversedBy="recaudos")
     * @ORM\JoinColumn(name="tipo_recaudo_id", referencedColumnName="id")
     */
    protected $tipo_recaudo;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario", inversedBy="recaudos")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $usuario;
    
    

    function __construct($name = null, \DateTime $fecha_vencimiento = null){
        $this->name = $name;
        $this->fecha_vencimiento = $fecha_vencimiento;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        // check if we have an old pdf path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/recaudos';
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
     * Set name
     *
     * @param string $name
     * @return Recaudo
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

    /**
     * Set path
     *
     * @param string $path
     * @return Recaudo
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->path = $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }
        // check if we have an old pdf
        if (isset($this->temp)) {

            if(file_exists($this->getUploadRootDir().'/'.$this->id.".".$this->temp)){
                // delete the old pdf
                unlink($this->getUploadRootDir().'/'.$this->id.".".$this->temp);
            }
            // clear the temp pdf path
            $this->temp = null;
        }
        // si hay un error al mover el archivo, move() automáticamente
        // envía una excepción. This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->id.'.'.$this->getFile()->guessExtension()
        );
        /* Actualizamos al nuevo Path
         * para que se guarde en la base de datos el nombre asignado al archivo*/
        $this->setPath($this->id.'.'.$this->path);

        // limpia la propiedad «file» ya que no la necesitas más
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Set tramite
     *
     * @return recaudo
     */
    public function setTramite(Tramite $tramite = null)
    {
        $this->tramite = $tramite;
        return $this;
    }

    /**
     * Get tramite
     *
     * @return \TramiteBundle\Entity\Tramite
     */
    public function getTramite()
    {
        return $this->tramite;
    }

    public function __toString() {
        return sprintf('%d.pdf', $this->id.' ('.$this->getUploadRootDir().')'.' ('.$this->getWebPath().')');
    }

    /**
     * Set tipo_recaudo
     *
     * @param \TramiteBundle\Entity\TipoRecaudo $tipo_recaudo
     *
     * @return Recaudo
     */
    public function setTipoRecaudo(TipoRecaudo $tipo_recaudo = null)
    {
        $this->tipo_recaudo = $tipo_recaudo;

        return $this;
    }

    /**
     * Get tipo_recaudo
     *
     * @return \TramiteBundle\Entity\TipoRecaudo
     */
    public function getTipoRecaudo()
    {
        return $this->tipo_recaudo;
    }

    /**
     * Set fecha_vencimiento
     * @param datetime $fecha_vencimiento
     */
    public function setFechaVencimiento($fecha_vencimiento)
    {
        $this->fecha_vencimiento = $fecha_vencimiento;
    }

    /**
     * Get fecha_vencimiento
     * @return datetime
     */
    public function getFechaVencimiento()
    {
        return $this->fecha_vencimiento;
    }
    
    /**
     * Get usuario
     *
     * @return integer
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    
    /**
     * Set estatus
     *
     * @param Usuario $usuario
     *
     * @return Recaudo
     */
    public function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }
    
    /**
     * Set tabla
     *
     * @param string $tabla
     */
    public function setTabla($tabla)
    {
        $this->tabla = $tabla;
        return $this;
    }

    /**
     * Get tabla
     *
     * @return string
     */
    public function getTabla()
    {
        return $this->tabla;
    }
    
    /**
     * Set valor
     * @param string $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Get valor
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }
}