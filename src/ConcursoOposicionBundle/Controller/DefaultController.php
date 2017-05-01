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
use ConcursoOposicionBundle\Entity\Recusacion;
use AppBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends Controller
{
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

                $fecha = $this->cambiarFormatoFecha($request->get("Inicio"));

                $concurso->setFechaInicio(date_create($fecha));

                $concurso->setNroVacantes(intval($request->get("Vacantes")));

                $concurso->setIdUsuario($this->getUser()->getId());
        
                if ($request->get("fechaDoc") != null || $request->get("fechaDoc") != "")
                {
                    $fecha = $this->cambiarFormatoFecha($request->get("fechaDoc"));
                    $concurso->setFechaRecepDoc(date_create($fecha));
                }
                
                if ($request->get("fechaPre") != null || $request->get("fechaPre") != "")
                {
                    $fecha = $this->cambiarFormatoFecha($request->get("fechaPre"));
                    $concurso->setFechaPresentacion(date_create($fecha));
                }
                
                $concurso->setObservaciones($request->get("observacion"));

                $concurso->setTipo($request->get("tipo"));

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
     * @Route("/concursoOposicion/registroJuradosAjax", name="registroJuradosAjax")
     * @Method("POST")
     */
    public function registroJuradosAjaxAction(Request $request){

        if($request->isXmlHttpRequest())
        {
            $encontrado = false;

            foreach ($this->getUser()->getRoles() as $rol => $valor) {
                
                if ($valor == "Asuntos Profesorales")
                    $encontrado = true;
            }

            if ($encontrado){

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

                $this->ConcursoJurado(intval($request->get("concurso")));

                return new JsonResponse("S");
            }
            else{
                return new JsonResponse("N");
            }            
        }
        else
             throw $this->createNotFoundException('Error al insertar datos');
    }

    private function ConcursoJurado($concurso){

        $em = $this->getDoctrine()->getManager();

        $idJurado = $this->getDoctrine()
                        ->getManager()
                        ->createQuery('SELECT MAX(j.id) AS lastId FROM ConcursosBundle:Jurado j')
                        ->getResult();

        $id = $idJurado[0]['lastId'];

        $jurado = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('ConcursosBundle:Jurado')
                    ->findOneById($id);

        $concursoObjeto = $em->getRepository('ConcursosBundle:Concurso')
                            ->findOneById(intval($concurso));

        $concursoObjeto->addJurado($jurado);

        $em->flush();
    }

    /**
     * @Route("/concursoOposicion/registroRecusacionAjax", name="registroRecusacionAjax")
     * @Method("POST")
     */
    public function registroRecusacionAjaxAction(Request $request){

        if($request->isXmlHttpRequest())
        {
            $encontrado = false;

            foreach ($this->getUser()->getRoles() as $rol => $valor) {
                
                if ($valor == "Asuntos Profesorales")
                    $encontrado = true;
            }

            if ($encontrado){

                $query = $this->getDoctrine()->getManager()->createQuery("select 1 from ConcursosBundle:Jurado a where '".$request->get("jurado")."' = a.cedula");

                $existeJ = $query->getResult();

                if ($existeJ != null){

                    $query = $this->getDoctrine()->getManager()->createQuery("select 1 from ConcursosBundle:Aspirante a where '".$request->get("aspirante")."' = a.cedula");

                    $existeA = $query->getResult();

                    if ($existeA != null){

                        $recusacion = new Recusacion();

                        $recusacion->setCedulaAspirante($request->get("aspirante"));
                        $recusacion->setCedulaJurado($request->get("jurado"));

                        $fecha = $this->cambiarFormatoFecha($request->get("fecha"));

                        $recusacion->setFecha(date_create($fecha));
                        $recusacion->setUsuario($this->getUser()->getId());

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($recusacion);
                        $em->flush();

                        return new JsonResponse("S");

                    } else
                        return new JsonResponse("A");                
                } else 
                    return new JsonResponse("J");                
            } else
                return new JsonResponse("N");          
        } else
             throw $this->createNotFoundException('Error al insertar datos');
    }

    public function cambiarFormatoFecha($fecha){

        $dia = substr($fecha, 0, 2);
        $mes = substr($fecha, 3, 2);
        $ano = substr($fecha, 6, 4);

        return $mes."/".$dia."/".$ano;
    }
}
