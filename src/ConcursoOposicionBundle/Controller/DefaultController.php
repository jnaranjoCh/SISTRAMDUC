<?php 

namespace ConcursoOposicionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ConcursosBundle\Entity\Concurso;
use ConcursosBundle\Entity\Aspirante;
use ConcursosBundle\Entity\Jurado;
use ConcursoOposicionBundle\Entity\Recusacion;
use ConcursoOposicionBundle\Entity\Curso;
use ConcursoOposicionBundle\Entity\Responsable;
use AppBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;
use ConcursosBundle\Entity\Resultado;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DefaultController extends Controller
{
	/**
	 * @Route("/concursoOposicion/actaVCAP", name="actaVCAP")
	 */
	public function actaVCAPAction()
	{
		return $this->render('ConcursoOposicionBundle::actaVerificacionCAP.html.twig');
	}

    /**
     * @Route("/concursoOposicion/requisicionRRHH", name="requisicionRRHH")
     */
    public function requisicionRRHHAction()
    {
        return $this->render('ConcursoOposicionBundle::requisicionRRHH.html.twig');
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
            $rol = "";

            foreach ($this->getUser()->getRoles() as $rol => $valor) {
                
                if ($valor == "Asuntos Profesorales" || $valor == "Jefe de Departamento o equivalente" || $valor == "Jefe de Cátedra o equivalente"){
                    $encontrado = true;
                    $rol = $valor;
                }
            }

            if ($encontrado){

                $concurso = new Concurso();

                $concurso->setFechaInicio(date_create());
                $concurso->setNroVacantes(intval($request->get("cargo")));
                $concurso->setIdUsuario($this->getUser()->getId());
                $concurso->setTipo("Oposicion");
                $concurso->setCondicion($request->get("ordinario"));
                $concurso->setTiempoDedicacion($request->get("dedicacion"));
                $concurso->setNroHoras($request->get("horas"));
                $concurso->setSede($request->get("sede"));
                $concurso->setCiudad($request->get("ciudad"));
                $concurso->setFacultad($request->get("facultad"));
                $concurso->setDepartamento($request->get("departamento"));

                if ($request->get("unidad") != '')
                    $concurso->setAreaPostulacion($request->get("unidad"));
                else $concurso->setAreaPostulacion("");

                if ($request->get("catedra") != '')
                    $concurso->setEscuela($request->get("catedra"));

                $concurso->setMotivo($request->get("motivo"));

                if ($request->get("motivo_descripcion") != '')
                    $concurso->setDescMotivo($request->get("motivo_descripcion"));
                
                $concurso->setJustificacion($request->get("justificacion"));
                $concurso->setGradoAcademico($request->get("grado"));
                $concurso->setProfesion($request->get("profesion"));
                $concurso->setExperiencia($request->get("experiencia"));
                $concurso->setAreaConocimiento($request->get("conocimiento"));

                if ($request->get("investigacion") != '')
                    $concurso->setAreaInvestigacion($request->get("investigacion"));

                if ($request->get("extension") != '')
                    $concurso->setAreaExtension($request->get("extension"));

                $concurso->setStatus("Esperando Por Presupuesto");

                $cursos = explode("|", $request->get("cadenaCurso"));
                $tiempos = explode("|", $request->get("cadenaTiempo"));
                 
                for ($i=0; $i < intval($request->get("tamano")); $i++) { 
                    
                    $curso = new Curso();
                    $curso->setNombre($cursos[$i]);
                    $curso->setTiempo($tiempos[$i]);
                    $concurso->addCurso($curso);
                }  

                $responsable = new Responsable();
                $responsable->setCargoR($rol);
                $responsable->setNyAR($this->getUser()->getNombreCorto());
                $responsable->setCedulaR($this->getUser()->getCedula());
                $responsable->setControl($request->get("control"));
                $responsable->setFirmaR("no se que se coloca");
                $responsable->setFechaR(date_create());

                $concurso->addResponsable($responsable);

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

                $em = $this->getDoctrine()->getManager();
                $query = $em->createQuery(
                    'SELECT p
                       FROM ConcursosBundle:Jurado p
                      WHERE p.cedula = :cedula and p.tipo = :tipo'
                )->setParameter('cedula', intval($request->get("cedula")))->setParameter('tipo', $request->get("tipo"));
                 
                $existe = $query->getResult();

                if ($existe == null){

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

                    $this->ConcursoJurado(intval($request->get("concurso")), $request->get("cedula"), $request->get("tipo"));
                } else {  

                    try{
                        $this->ConcursoJuradoExistente(intval($request->get("concurso")), $existe[0]);
                        
                    } catch(\Doctrine\DBAL\DBALException $e){

                        return new JsonResponse("S");
                    }
                }                

                return new JsonResponse("S");
            }
            else{
                return new JsonResponse("N");
            }            
        }
        else
             throw $this->createNotFoundException('Error al insertar datos');
    }

    private function ConcursoJuradoExistente($concurso, $jurado){

        $em = $this->getDoctrine()->getManager();

        $concursoObjeto = $em->getRepository('ConcursosBundle:Concurso')
                            ->findOneById(intval($concurso));

        $concursoObjeto->addJurado($jurado);

        $em->flush();                
    }

    private function ConcursoJurado($concurso, $cedula, $tipo){

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
        		'SELECT p
                       FROM ConcursosBundle:Jurado p
                      WHERE p.cedula = :cedula and p.tipo = :tipo'
        		)->setParameter('cedula', $cedula)
        		->setParameter('tipo', $tipo);
        		 
        $jurado = $query->getResult();
        
        $concursoObjeto = $em->getRepository('ConcursosBundle:Concurso')
                            ->findOneById(intval($concurso));

        $concursoObjeto->addJurado($jurado[0]);

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

                $recusacion = new Recusacion();

                $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Aspirante');
             
                $query = $repository->createQueryBuilder('p')
                    ->where('p.id = :cadena')
                    ->setParameter('cadena', $request->get("aspirante"))
                    ->getQuery();
                 
                $aspirante = $query->getResult();

                $recusacion->setAspiranteId($aspirante[0]);

                $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Jurado');
             
                $query = $repository->createQueryBuilder('p')
                    ->where('p.id = :cadena')
                    ->setParameter('cadena', $request->get("jurado"))
                    ->getQuery();
                 
                $jurado = $query->getResult();

                $recusacion->setJuradoId($jurado[0]);

                $recusacion->setFecha(date_create(""));
                $recusacion->setUsuario($this->getUser()->getId());

                $em = $this->getDoctrine()->getManager();
                $em->persist($recusacion);
                $em->flush();

                return new JsonResponse("S");
                          
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

    /**
     * @Route("/concursoOposicion/registroAspiranteAjax", name="registroAspiranteAjax")
     * @Method("POST")
     */
    public function registroAspiranteAjaxAction(Request $request){

        if($request->isXmlHttpRequest())
        {
            $encontrado = false;

            foreach ($this->getUser()->getRoles() as $rol => $valor) {
                
                if ($valor == "Asuntos Profesorales")
                    $encontrado = true;
            }

            if ($encontrado){               

                $em = $this->getDoctrine()->getManager();
                $query = $em->createQuery(
                    'SELECT p
                       FROM ConcursosBundle:Aspirante p
                      WHERE p.cedula = :cedula')->setParameter('cedula', intval($request->get("cedula")));
                 
                $existe = $query->getResult();

                if ($existe == null){

                    $aspirante = new Aspirante();

                    $aspirante->setPrimerNombre($request->get("nombre1"));
                    $aspirante->setSegundoNombre($request->get("nombre2"));
                    $aspirante->setPrimerApellido($request->get("apellido1"));
                    $aspirante->setSegundoApellido($request->get("apellido2"));
                    $aspirante->setTelefono($request->get("tlf1"));
                    $aspirante->setCorreo($request->get("email1"));
                    $aspirante->setCedula($request->get("cedula"));
                    $aspirante->setTelefonoSecundario($request->get("tlf2"));
                    $aspirante->setUniversidadEgresado($request->get("universidad"));
                    $aspirante->setDescripcionTituloUniv($request->get("tiulo"));
                    $aspirante->setAnyoGraduacion($request->get("graduacion"));
                    $aspirante->setObservaciones($request->get("observacion"));

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($aspirante);
                    $em->flush();

                    $this->ConcursoAspirante(intval($request->get("concurso")), $request->get("cedula"));

                    return new JsonResponse("S");

                } else {

                    try{
                        $this->ConcursoAspiranteExistente(intval($request->get("concurso")), $existe[0]);
                        
                    } catch(\Doctrine\DBAL\DBALException $e){

                        return new JsonResponse("S");
                    }

                    return new JsonResponse("S");
                } 
            }
            else{
                return new JsonResponse("N");
            }            
        }
        else
             throw $this->createNotFoundException('Error al insertar datos');
    }

    private function ConcursoAspiranteExistente($concurso, $aspirante){

        $em = $this->getDoctrine()->getManager();

        $concursoObjeto = $em->getRepository('ConcursosBundle:Concurso')
                            ->findOneById(intval($concurso));

        $concursoObjeto->addAspirante($aspirante);

        $em->flush();                
    }

    private function ConcursoAspirante($concurso, $cedula){

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
        		'SELECT p
                       FROM ConcursosBundle:Aspirante p
                      WHERE p.cedula = :cedula'
        		)->setParameter('cedula', $cedula);
        		 
        $jurado = $query->getResult();

        $concursoObjeto = $em->getRepository('ConcursosBundle:Concurso')
                            ->findOneById(intval($concurso));

        $concursoObjeto->addAspirante($jurado[0]);

        $em->flush();
    }
    
    /**
     * @Route("/concursoOposicion/registrarResultadoAjax", name="registrarResultadoAjax")
     * @Method("POST")
     */
    public function registrarResultadoAjaxAction(Request $request){
    
    	if($request->isXmlHttpRequest())
    	{
    		$encontrado = false;
    
    		foreach ($this->getUser()->getRoles() as $rol => $valor) {
    
    			if ($valor == "Asuntos Profesorales")
    				$encontrado = true;
    		}
    		    
    		if ($encontrado){   			
    			
    			if ($request->get("id") == 0){
    				
    				$resultado = new Resultado();
    				 
    				$resultado->setAptitud(intval($request->get("intelectual")));
    				$resultado->setCedulaAspirante($request->get("cedula"));
    				$resultado->setIdConcurso(intval($request->get("concurso")));
    				$resultado->setNota(intval($request->get("credenciales")));
    				$resultado->setNotaEscrito(intval($request->get("area")));
    				$resultado->setNotaOral(intval($request->get("pedagogica")));
    				$resultado->setPsicologica(intval($request->get("academico")));
    				$resultado->setResponsable($this->getUser()->getId());   				
    				$suma = $resultado->getNota()+$resultado->getNotaEscrito()+$resultado->getNotaOral()+$resultado->getPsicologica()+$resultado->getAptitud();
    				
    				$resultado->setResultado($suma);
    				
    				$em = $this->getDoctrine()->getManager();
    				$em->persist($resultado);
    				$em->flush();
    				
    			} else {
    				
    				$em = $this->getDoctrine()->getManager();
    				
    				$resultado = $em->getRepository('ConcursosBundle:Resultado')->find($request->get("id"));
    				
    				$resultado->setAptitud($request->get("intelectual"));
    				$resultado->setNota($request->get("credenciales"));
    				$resultado->setNotaEscrito($request->get("area"));
    				$resultado->setNotaOral($request->get("pedagogica"));
    				$resultado->setPsicologica($request->get("academico"));
    				$resultado->setResponsable($this->getUser()->getId());
    				$suma = $resultado->getNota()+$resultado->getNotaEscrito()+$resultado->getNotaOral()+$resultado->getPsicologica()+$resultado->getAptitud();
    				$resultado->setResultado($suma);
    				
    				$em->flush();
    			}
    			
    			return new JsonResponse($this->getUser()->getNombreCorto());
    		}
    		else{
    			return new JsonResponse("N");
    		}
    	}
    	else
    		throw $this->createNotFoundException('Error al insertar datos');
    }


     /**
     * @Route("/concursoOposicion/pdfAjax", name="pdfAjax")
     * @Method("POST")
     */
    public function pdfAjaxAction(Request $request){
    
        if($request->isXmlHttpRequest())
        {
            
            $pdf = new \FPDF();

            $pdf->AddPage();
            $pdf->SetFont('Arial','B',16);
            $pdf->Cell(40,10,'Hello World!');
            
            /*
            $response = new Response($pdf->Output(), Response::HTTP_OK, array('content-Type' => 'application/pdf'));

            $d = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'foo.pdf');

            $response->headers->set('Content-Disposition', $d);

            return $response;
            */
            

            $retorna = new Response($pdf->Output(), 200, array(
                'Content-Type' => 'application/pdf'));

            return $retorna;
                      
        }
        else
            throw $this->createNotFoundException('Error al ejecutar pdf');
    }  
}
