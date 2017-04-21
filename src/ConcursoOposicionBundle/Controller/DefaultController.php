<?php 

namespace ConcursoOposicionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ConcursosBundle\Entity\Concurso;
use ConcursosBundle\Entity\Jurado;
use AppBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends Controller
{
    private $idUsuario;

    /**
     * @Route("/concursoOposicion/apertura_concurso_oposicion_index", name="apertura_concurso_oposicion_index")
     */
    public function aperturaDeConcursoAction()
    {
        return $this->render('ConcursoOposicionBundle::apertura_concurso.html.twig');
    }

    /**
     * @Route("/concursoOposicion/jurado", name="jurado")
     */
    public function juradoAction()
    {
        return $this->render('ConcursoOposicionBundle::jurado.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listarSuplentesCPEC", name="listarSuplentesCPEC")
     */
    public function listarSuplentesCpecAction()
    {
        return $this->render('ConcursoOposicionBundle::listarSuplentesCPEC.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listarCPEC", name="listarCPEC")
     */
    public function listadoCpecAction()
    {
        return $this->render('ConcursoOposicionBundle::listarCPEC.html.twig');
    }

    /**
     * @Route("/concursoOposicion/cpec", name="cpec")
     */
    public function cpecAction()
    {
        return $this->render('ConcursoOposicionBundle::cpec.html.twig');
    }

    /**
     * @Route("/concursoOposicion/tablaBasicaConcurso", name="tablaBasicaConcurso")
     */
    public function concursoAction()
    {
        return $this->render('ConcursoOposicionBundle::listarConcurso.html.twig');
    }

    /**
     * @Route("/concursoOposicion/registro_usuario_oposicion", name="registro_usuario_oposicion")
     */
    public function registroAction()
    {
        return $this->render('ConcursoOposicionBundle::registroAspirante.html.twig');
    }

    /**
     * @Route("/concursoOposicion/documentacion_oposicion", name="documentacion_oposicion")
     */
    public function documentacionAction()
    {
        return $this->render('ConcursoOposicionBundle::documentacion.html.twig');
    }

    /**
     * @Route("/concursoOposicion/recusacion_oposicion", name="recusacion_oposicion")
     */
    public function recusacionAction()
    {
        return $this->render('ConcursoOposicionBundle::recusacion.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listaAspirantes", name="listaAspirantes")
     */
    public function listaAspirantesAction()
    {
        return $this->render('ConcursoOposicionBundle::listaAspirantes.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listaRecusacion", name="listaRecusacion")
     */
    public function listaRecusacionAction()
    {
        return $this->render('ConcursoOposicionBundle::listaRecusacion.html.twig');
    }

    /**
     * @Route("/concursoOposicion/suplentesJurado", name="suplentesJurado")
     */
    public function suplentesJuradoAction()
    {
        return $this->render('ConcursoOposicionBundle::suplentesJurado.html.twig');
    }

    /**
     * @Route("/concursoOposicion/suplentesCPEC", name="suplentesCPEC")
     */
    public function suplentesCPECAction()
    {
        return $this->render('ConcursoOposicionBundle::suplentesCPEC.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listarJurados", name="listarJurados")
     */
    public function listarJuradosAction()
    {
        return $this->render('ConcursoOposicionBundle::listarJurados.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listarSuplentes", name="listarSuplentes")
     */
    public function listarSuplentesAction()
    {
        return $this->render('ConcursoOposicionBundle::listarSuplentes.html.twig');
    }

    /**
     * @Route("/concursoOposicion/pruebas", name="pruebas")
     */
    public function pruebasAction()
    {
        return $this->render('ConcursoOposicionBundle::pruebas.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listarPruebas", name="listarPruebas")
     */
    public function listarPruebasAction()
    {
        return $this->render('ConcursoOposicionBundle::listarPruebas.html.twig');
    }

    /**
     * @Route("/concursoOposicion/resultados", name="resultados")
     */
    public function resultadosAction()
    {
        return $this->render('ConcursoOposicionBundle::resultados.html.twig');
    }

    /**
     * @Route("/concursoOposicion/listarResultados", name="listarResultados")
     */
    public function listarResultadosAction()
    {
        return $this->render('ConcursoOposicionBundle::listarResultados.html.twig');
    }

    /**
     * @Route("/concursoOposicion/registroConcursoAjax", name="registroConcursoAjax")
     * @Method("POST")
     */
    public function registroConcursoAjaxAction(Request $request){

        if($request->isXmlHttpRequest()){

            $encontrado = false;

            foreach ($this->getUser()->getRoles() as $rol => $valor) {
                
                if ($valor == "Asuntos Profesorales")
                    $encontrado = true;
            }

            if ($encontrado){

                $concurso = new Concurso();

               $concurso->setFechaInicio(date_create($request->get("Inicio")));

                $concurso->setNroVacantes($request->get("Vacantes"));

                $concurso->setIdUsuario($this->getUser()->getId());
        
                $concurso->setFechaRecepDoc(date_create($request->get("fechaDoc")));
                            
                $concurso->setFechaPresentacion(date_create($request->get("fechaPre")));
                
                $concurso->setObservaciones($request->get("observacion"));

                $concurso->setAreaPostulacion($request->get("Area"));

                $em = $this->getDoctrine()->getManager();
                $em->persist($concurso);
                $em->flush();

                return new JsonResponse("S");

            } else {

                return new JsonResponse("N");
            }               
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');      
    }

     /**
     * @Route("/concursoOposicion/listadoConcursosAjax", name="listadoConcursosAjax")
     * @Method("POST")
     */
    public function listadoConcursosAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $concurso = $this->getAll("ConcursosBundle:", "Concurso");

            if (!$concurso) {
                 throw $this->createNotFoundException('Error al obtener datos iniciales');
            }else
            {
                $val = $this->asignarFilaNroVacantes($concurso,'vacantes',$val);
                $val = $this->asignarFilaAreaPostulacion($concurso,'area',$val);
                $val = $this->asignarFilaFechaInicio($concurso,'inicio',$val);
                $val = $this->asignarFilaFechaRecepcion($concurso,'recepcion',$val);
                $val = $this->asignarFilaFechaPresentacion($concurso,'presentacion',$val);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al insertar datos');
    }

    private function asignarFilaFechaPresentacion($object,$entidad,$val)
    {
        $i = 0;
        foreach($object as $value)
        {
           $val[$entidad][$i] = $value->getFechaPresentacion();
           $i++;
        }
        return $val;
    }

    private function asignarFilaFechaRecepcion($object,$entidad,$val)
    {
        $i = 0;
        foreach($object as $value)
        {
           $val[$entidad][$i] = $value->getFechaRecepDoc();
           $i++;
        }
        return $val;
    }

    private function asignarFilaNroVacantes($object,$entidad,$val)
    {
        $i = 0;
        foreach($object as $value)
        {
           $val[$entidad][$i] = $value->getNroVacantes();
           $i++;
        }
        return $val;
    }

    private function asignarFilaAreaPostulacion($object,$entidad,$val)
    {
        $i = 0;
        foreach($object as $value)
        {
           $val[$entidad][$i] = $value->getAreaPostulacion();
           $i++;
        }
        return $val;
    }

    private function asignarFilaFechaInicio($object,$entidad,$val)
    {
        $i = 0;
        foreach($object as $value)
        {
           $val[$entidad][$i] = $value->getFechaInicio();
           $i++;
        }
        return $val;
    }

    private function getAll($bundle,$entidad)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findAll();
    }

    /**
     * @Route("/concursoOposicion/registroJuradosAjax", name="registroJuradosAjax")
     * @Method("POST")
     */
    public function registroJuradosAjaxAction(Request $request){

        if($request->isXmlHttpRequest())
        {
            $jurado = new Jurado();

            $jurado->setNombre($request->get("nombre"));
            $jurado->setApellido($request->get("apellido"));
            $jurado->setAreaInvestigacion($request->get("area"));
            $jurado->setFacultad($request->get("facultad"));
            $jurado->setUniversidad($request->get("universidad"));
            $jurado->setIdUsuarioAsigna($this->getUser()->getId());
            $jurado->setTipo($request->get("tipo"));
            $jurado->setCedula($request->get("cedula"));

            $em = $this->getDoctrine()->getManager();
            $em->persist($jurado);
            $em->flush();

            return new JsonResponse("S");
        }
        else
             throw $this->createNotFoundException('Error al insertar datos');
    }
}
