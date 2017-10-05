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
use ConcursoOposicionBundle\Entity\Responsable;
use ConcursoOposicionBundle\Entity\Curso;

class ListadosOposicionController extends Controller
{
     /**
     * @Route("/concursoOposicion/listadoConcursosAjax", name="listadoConcursosAjax")
     * @Method("POST")
     */
    public function listadoConcursosAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Concurso');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.tipo = :cadena')
                ->setParameter('cadena', 'Oposicion')
                ->orderBy('p.id', 'DESC')
                ->getQuery();
             
            $concurso = $query->getResult();

            if (!$concurso) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaConcursos($concurso,'id',$val, 0);
                $val = $this->asignarFilaConcursos($concurso,'fechaInicio',$val, 1);
                $val = $this->asignarFilaConcursos($concurso,'nroVacantes',$val, 2);
                $val = $this->asignarFilaConcursos($concurso,'areaPostulacion',$val, 3);
                $val = $this->asignarFilaConcursos($concurso,'idUsuario',$val, 4);
                $val = $this->asignarFilaConcursos($concurso,'condicion',$val, 5);
                $val = $this->asignarFilaConcursos($concurso,'tiempo_dedicacion',$val, 6);
                $val = $this->asignarFilaConcursos($concurso,'nro_horas',$val, 7);
                $val = $this->asignarFilaConcursos($concurso,'facultad',$val, 8);
                $val = $this->asignarFilaConcursos($concurso,'sede',$val, 9);
                $val = $this->asignarFilaConcursos($concurso,'ciudad',$val, 10);
                $val = $this->asignarFilaConcursos($concurso,'escuela',$val, 11);
                $val = $this->asignarFilaConcursos($concurso,'departamento',$val, 12);
                $val = $this->asignarFilaConcursos($concurso,'motivo',$val, 13);
                $val = $this->asignarFilaConcursos($concurso,'desc_motivo',$val, 14);
                $val = $this->asignarFilaConcursos($concurso,'justificacion',$val, 15);
                $val = $this->asignarFilaConcursos($concurso,'grado_academico',$val, 16);
                $val = $this->asignarFilaConcursos($concurso,'profesion',$val, 17);
                $val = $this->asignarFilaConcursos($concurso,'experiencia',$val, 18);
                $val = $this->asignarFilaConcursos($concurso,'area_conocimiento',$val, 19);
                $val = $this->asignarFilaConcursos($concurso,'area_investigacion',$val, 20);
                $val = $this->asignarFilaConcursos($concurso,'area_extension',$val, 21);
                $val = $this->asignarFilaConcursos($concurso,'status',$val, 22);
                $val = $this->asignarFilaConcursos($concurso,'idUsuarioAct',$val, 23);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    /**
     * @Route("/concursoOposicion/buscarActasAjax", name="buscarActasAjax")
     * @Method("POST")
     */
    public function buscarActasAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();

            $concurso = $em->getRepository('ConcursosBundle:Concurso')->find(intval($request->get("id")));

            $acta = $concurso->getActa();

            if (!$acta) {
                 return new JsonResponse("N");
            }else {

                $val = $this->asignarFilaActas($acta,'id',$val, 0);
                $val = $this->asignarFilaActas($acta,'fecha',$val, 1);
                $val = $this->asignarFilaActas($acta,'lugar',$val, 2);
                $val = $this->asignarFilaActas($acta,'asunto',$val, 3);
                $val = $this->asignarFilaActas($acta,'resolucion',$val, 4);
                $val = $this->asignarFilaActas($acta,'avala',$val, 5);
                $val = $this->asignarFilaActas($acta,'justificacion',$val, 6);
                $val = $this->asignarFilaActas($acta,'ampm',$val, 7);
                $val = $this->asignarFilaActas($acta,'nro',$val, 8);

                return new JsonResponse($val);
            }            
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    private function asignarFilaActas($object,$entidad,$val, $pos)
    {

       switch ($pos) {
           case 1: $val[$entidad][0] = date_format($object->getFecha(), 'd-m-Y'); break;
           case 2: $val[$entidad][0] = $object->getLugar(); break;
           case 3: $val[$entidad][0] = $object->getAsunto();break;
           case 4: $val[$entidad][0] = $object->getResolucion();break;
           case 5: $val[$entidad][0] = $object->getAvala();break;
           case 6: $val[$entidad][0] = $object->getJustificacion();break;
           case 7: $val[$entidad][0] = $object->getAmpm();break;
           case 8: $val[$entidad][0] = $object->getNroActa();break;

           default: $val[$entidad][0] = $object->getId(); break;
        }
        return $val;
    }

    /**
     * @Route("/concursoOposicion/buscarCursoAjax", name="buscarCursoAjax")
     * @Method("POST")
     */
    public function buscarCursoAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $repository = $this->getDoctrine()
                ->getRepository('ConcursoOposicionBundle:Curso');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.concurso = :cadena')
                ->setParameter('cadena', $request->get("id"))
                ->orderBy('p.id', 'DESC')
                ->getQuery();
             
            $concurso = $query->getResult();

            if (!$concurso) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaCursos($concurso,'tiempo',$val, 0);
                $val = $this->asignarFilaCursos($concurso,'curso',$val, 1);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

     private function asignarFilaCursos($object,$entidad,$val, $pos)
    {
        $i = 0;
        foreach($object as $value)
        {
           switch ($pos) {
               case 0: $val[$entidad][$i] = $value->getTiempo(); break;
               case 1: $val[$entidad][$i] = $value->getNombre(); break;
           }
           $i++;
        }
        return $val;
    }

    private function asignarFilaFechaPresentacion($object,$entidad,$val)
    {
        $i = 0;
        foreach($object as $value)
        {
            if ($value->getFechaPresentacion() != null)
                $val[$entidad][$i] = date_format($value->getFechaPresentacion(), 'd-m-Y');
            else
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
            if ($value->getFechaRecepDoc() != null)
                $val[$entidad][$i] = date_format($value->getFechaRecepDoc(), 'd-m-Y');
           else
                $val[$entidad][$i] = $value->getFechaRecepDoc();
           $i++;
        }
        return $val;
    }

    private function asignarFilaConcursos($object,$entidad,$val, $pos)
    {
        $i = 0;
        foreach($object as $value)
        {
           switch ($pos) {
               case 1: $val[$entidad][$i] = date_format($value->getFechaInicio(), 'd-m-Y'); break;
               case 2: $val[$entidad][$i] = $value->getNroVacantes(); break;
               case 3: $val[$entidad][$i] = $value->getAreaPostulacion();break;
               case 4: $val[$entidad][$i] = $value->getIdUsuario(); break;
               case 5: $val[$entidad][$i] = $value->getCondicion(); break;
               case 6: $val[$entidad][$i] = $value->getTiempoDedicacion(); break;
               case 7: $val[$entidad][$i] = $value->getNroHoras(); break;
               case 8: $val[$entidad][$i] = $value->getFacultad(); break;
               case 9: $val[$entidad][$i] = $value->getSede(); break;
               case 10: $val[$entidad][$i] = $value->getCiudad(); break;
               case 11: $val[$entidad][$i] = $value->getEscuela(); break;
               case 12: $val[$entidad][$i] = $value->getDepartamento(); break;
               case 13: $val[$entidad][$i] = $value->getMotivo(); break;
               case 14: $val[$entidad][$i] = $value->getDescMotivo(); break;
               case 15: $val[$entidad][$i] = $value->getJustificacion(); break;
               case 16: $val[$entidad][$i] = $value->getGradoAcademico(); break;
               case 17: $val[$entidad][$i] = $value->getProfesion(); break;
               case 18: $val[$entidad][$i] = $value->getExperiencia(); break;
               case 19: $val[$entidad][$i] = $value->getAreaConocimiento(); break;
               case 20: $val[$entidad][$i] = $value->getAreaInvestigacion(); break;
               case 21: $val[$entidad][$i] = $value->getAreaExtension(); break;
               case 22: $val[$entidad][$i] = $value->getStatus(); break;
               case 23: $val[$entidad][$i] = $value->getIdUsuarioAct(); break;

               default: $val[$entidad][$i] = $value->getId(); break;
           }
           $i++;
        }
        return $val;
    }

     /**
     * @Route("/concursoOposicion/listadoJuradosAjax", name="listadoJuradosAjax")
     * @Method("POST")
     */
    public function listadoJuradosAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
        	if ($request->get("id") != "NO"){
        		
        		$query = $this->getDoctrine()
		        		->getManager()
		        		->createQuery("SELECT u
				         FROM ConcursosBundle:Jurado u, ConcursosBundle:Concurso c
				         INNER JOIN  c.jurado r
				         WHERE c.id = :concurso and r = u.id and u.tipo = 'Oposicion'")
		        		         ->setParameter(':concurso', $request->get("id"));
        		
        	} else {
        	
	            $repository = $this->getDoctrine()
	                ->getRepository('ConcursosBundle:Jurado');
	             
	            $query = $repository->createQueryBuilder('p')
	                ->where('p.tipo = :cadena')
	                ->setParameter('cadena', 'Oposicion')
	                ->orderBy('p.id', 'DESC')
	                ->getQuery();
            
        	}
        	
            $jurados = $query->getResult();

            if (!$jurados) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaJurado($jurados,'id',$val, 0);
                $val = $this->asignarFilaJurado($jurados,'nombre',$val, 1);
                $val = $this->asignarFilaJurado($jurados,'apellido',$val, 2);
                $val = $this->asignarFilaJurado($jurados,'areainvestigacion',$val, 3);
                $val = $this->asignarFilaJurado($jurados,'facultad',$val, 4);
                $val = $this->asignarFilaJurado($jurados,'universidad',$val, 5);
                $val = $this->asignarFilaJurado($jurados,'idusuarioasigna',$val, 6);
                $val = $this->asignarFilaJurado($jurados,'cedula',$val, 7);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    private function asignarFilaJurado($object,$entidad,$val, $pos)
    {
        $i = 0;
        foreach($object as $value)
        {
           switch ($pos) {
               case 1: $val[$entidad][$i] = $value->getNombre(); break;
               case 2: $val[$entidad][$i] = $value->getApellido(); break;
               case 3: $val[$entidad][$i] = $value->getAreaInvestigacion(); break;
               case 4: $val[$entidad][$i] = $value->getFacultad(); break;
               case 5: $val[$entidad][$i] = $value->getUniversidad(); break;
               case 6: $val[$entidad][$i] = $this->usuarioAsigna($value->getIdUsuarioAsigna()); break;
               case 7: $val[$entidad][$i] = $value->getCedula(); break;

               default: $val[$entidad][$i] = $value->getId(); break;
           }
           $i++;
        }
        return $val;
    }

    private function usuarioAsigna($id){

        if ($id == null)
            return "";
        else {

            $repository = $this->getDoctrine()
                ->getRepository('AppBundle:Usuario');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.id = :cadena')
                ->setParameter('cadena', $id)
                ->getQuery();
             
            $usuario = $query->getResult();

            if ($usuario == null) return "";
            else return $usuario[0]->getNombreCorto();
        }
    }

     /**
     * @Route("/concursoOposicion/listadoSuplentesAjax", name="listadoSuplentesAjax")
     * @Method("POST")
     */
    public function listadoSuplentesAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
        	if ($request->get("id") != "NO"){
        	
        		$query = $this->getDoctrine()
        		->getManager()
        		->createQuery("SELECT u
				         FROM ConcursosBundle:Jurado u, ConcursosBundle:Concurso c
				         INNER JOIN  c.jurado r
				         WHERE c.id = :concurso and r = u.id and u.tipo = 'OposicionSuplentes'")
        					         ->setParameter(':concurso', $request->get("id"));
        	
        	} else {
        		
        		$repository = $this->getDoctrine()
        		->getRepository('ConcursosBundle:Jurado');
        		 
        		$query = $repository->createQueryBuilder('p')
        		->where('p.tipo = :cadena')
        		->setParameter('cadena', 'OposicionSuplentes')
        		->orderBy('p.id', 'DESC')
        		->getQuery();
        	}
    
            $jurados = $query->getResult();

            if (!$jurados) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaJurado($jurados,'id',$val, 0);
                $val = $this->asignarFilaJurado($jurados,'nombre',$val, 1);
                $val = $this->asignarFilaJurado($jurados,'apellido',$val, 2);
                $val = $this->asignarFilaJurado($jurados,'areainvestigacion',$val, 3);
                $val = $this->asignarFilaJurado($jurados,'facultad',$val, 4);
                $val = $this->asignarFilaJurado($jurados,'universidad',$val, 5);
                $val = $this->asignarFilaJurado($jurados,'idusuarioasigna',$val, 6);
                $val = $this->asignarFilaJurado($jurados,'cedula',$val, 7);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

     /**
     * @Route("/concursoOposicion/listadoCPECAjax", name="listadoCPECAjax")
     * @Method("POST")
     */
    public function listadoCPECAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
        	if ($request->get("id") != "NO"){
        		 
        		$query = $this->getDoctrine()
        		->getManager()
        		->createQuery("SELECT u
				         FROM ConcursosBundle:Jurado u, ConcursosBundle:Concurso c
				         INNER JOIN  c.jurado r
				         WHERE c.id = :concurso and r = u.id and u.tipo = 'OposicionCpec'")
        					         ->setParameter(':concurso', $request->get("id"));
        					          
        	} else {
        	
            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Jurado');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.tipo = :cadena')
                ->setParameter('cadena', 'OposicionCpec')
                ->orderBy('p.id', 'DESC')
                ->getQuery();
        	} 
                
            $jurados = $query->getResult();

            if (!$jurados) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaJurado($jurados,'id',$val, 0);
                $val = $this->asignarFilaJurado($jurados,'nombre',$val, 1);
                $val = $this->asignarFilaJurado($jurados,'apellido',$val, 2);
                $val = $this->asignarFilaJurado($jurados,'areainvestigacion',$val, 3);
                $val = $this->asignarFilaJurado($jurados,'facultad',$val, 4);
                $val = $this->asignarFilaJurado($jurados,'universidad',$val, 5);
                $val = $this->asignarFilaJurado($jurados,'idusuarioasigna',$val, 6);
                $val = $this->asignarFilaJurado($jurados,'cedula',$val, 7);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

     /**
     * @Route("/concursoOposicion/listadoSuplenteCPECAjax", name="listadoSuplenteCPECAjax")
     * @Method("POST")
     */
    public function listadoSuplenteCPECAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
        	if ($request->get("id") != "NO"){
        		 
        		$query = $this->getDoctrine()
        		->getManager()
        		->createQuery("SELECT u
				         FROM ConcursosBundle:Jurado u, ConcursosBundle:Concurso c
				         INNER JOIN  c.jurado r
				         WHERE c.id = :concurso and r = u.id and u.tipo = 'OposicionSuplenteCpec'")
        					         ->setParameter(':concurso', $request->get("id"));
        					         	
        	} else {
        		
        		$repository = $this->getDoctrine()
        		->getRepository('ConcursosBundle:Jurado');
        		 
        		$query = $repository->createQueryBuilder('p')
        		->where('p.tipo = :cadena')
        		->setParameter('cadena', 'OposicionSuplenteCpec')
        		->orderBy('p.id', 'DESC')
        		->getQuery();
        	}           
             
            $jurados = $query->getResult();

            if (!$jurados) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaJurado($jurados,'id',$val, 0);
                $val = $this->asignarFilaJurado($jurados,'nombre',$val, 1);
                $val = $this->asignarFilaJurado($jurados,'apellido',$val, 2);
                $val = $this->asignarFilaJurado($jurados,'areainvestigacion',$val, 3);
                $val = $this->asignarFilaJurado($jurados,'facultad',$val, 4);
                $val = $this->asignarFilaJurado($jurados,'universidad',$val, 5);
                $val = $this->asignarFilaJurado($jurados,'idusuarioasigna',$val, 6);
                $val = $this->asignarFilaJurado($jurados,'cedula',$val, 7);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    /**
     * @Route("/concursoOposicion/buscarConcursoAjax", name="buscarConcursoAjax")
     * @Method("POST")
     */
    public function buscarConcursoAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Concurso');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.id = :id')
                ->setParameter('id', $request->get("id"))
                ->getQuery();
             
            $concurso = $query->getResult();

            if (!$concurso) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaConcursos($concurso,'id',$val, 0);
                $val = $this->asignarFilaConcursos($concurso,'fechaInicio',$val, 1);
                $val = $this->asignarFilaConcursos($concurso,'nroVacantes',$val, 2);
                $val = $this->asignarFilaConcursos($concurso,'areaPostulacion',$val, 3);
                $val = $this->asignarFilaConcursos($concurso,'idUsuario',$val, 4);
                $val = $this->asignarFilaConcursos($concurso,'condicion',$val, 5);
                $val = $this->asignarFilaConcursos($concurso,'tiempo_dedicacion',$val, 6);
                $val = $this->asignarFilaConcursos($concurso,'nro_horas',$val, 7);
                $val = $this->asignarFilaConcursos($concurso,'facultad',$val, 8);
                $val = $this->asignarFilaConcursos($concurso,'sede',$val, 9);
                $val = $this->asignarFilaConcursos($concurso,'ciudad',$val, 10);
                $val = $this->asignarFilaConcursos($concurso,'escuela',$val, 11);
                $val = $this->asignarFilaConcursos($concurso,'departamento',$val, 12);
                $val = $this->asignarFilaConcursos($concurso,'motivo',$val, 13);
                $val = $this->asignarFilaConcursos($concurso,'desc_motivo',$val, 14);
                $val = $this->asignarFilaConcursos($concurso,'justificacion',$val, 15);
                $val = $this->asignarFilaConcursos($concurso,'grado_academico',$val, 16);
                $val = $this->asignarFilaConcursos($concurso,'profesion',$val, 17);
                $val = $this->asignarFilaConcursos($concurso,'experiencia',$val, 18);
                $val = $this->asignarFilaConcursos($concurso,'area_conocimiento',$val, 19);
                $val = $this->asignarFilaConcursos($concurso,'area_investigacion',$val, 20);
                $val = $this->asignarFilaConcursos($concurso,'area_extension',$val, 21);
                $val = $this->asignarFilaConcursos($concurso,'status',$val, 22);
                $val = $this->asignarFilaConcursos($concurso,'idUsuarioAct',$val, 23);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    /**
     * @Route("/concursoOposicion/borrarConcursoAjax", name="borrarConcursoAjax")
     * @Method("POST")
     */
    public function borrarConcursoAjaxAction(Request $request){

        if($request->isXmlHttpRequest())
        {
            $encontrado = false;

            foreach ($this->getUser()->getRoles() as $rol => $valor) {
                
                if ($valor == "Asuntos Profesorales")
                    $encontrado = true;
            }

            if ($encontrado){

                $em = $this->getDoctrine()->getManager();

                $concurso = $em->getRepository('ConcursosBundle:Concurso')->find($request->get("id"));

                $em->remove($concurso);

                $em->flush();
                
                return new JsonResponse("S");

            } else return new JsonResponse("N");             
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    /**
     * @Route("/concursoOposicion/modificarConcursoAjax", name="modificarConcursoAjax")
     * @Method("POST")
     */
    public function modificarConcursoAjaxAction(Request $request){

        if($request->isXmlHttpRequest())
        {       
            $em = $this->getDoctrine()->getManager();

            $concurso = $em->getRepository('ConcursosBundle:Concurso')->find(intval($request->get("id")));

            if (!$concurso) {

                return new JsonResponse("N");

            } else {

                $concurso->setStatus($request->get("status"));

                $responsable = new Responsable();

                $responsable->setNyAR($this->getUser()->getNombreCorto());
                $responsable->setFechaR(date_create());

                if ($request->get("status") == "Esperando Por Concejo De Escuela") {

                    $responsable->setPresupuesto($request->get("disponibilidad"));
                    $responsable->setFechaInicioResolucion(date_create($request->get("fecha")));
                    $responsable->setControl($request->get("control"));

                } else {

                    if ($request->get("status") == "Esperando Por Concejo De Asuntos Profesorales") {

                        $responsable->setControl($request->get("control"));

                    } else {

                        if ($request->get("status") == "Esperando Por Auditoría Académica" || $request->get("status") == "Esperando Por Concejo De Facultad" || $request->get("status") == "Aprobado") {

                            $responsable->setAvala($request->get("avala"));
                            $responsable->setJustificacion($request->get("justificacion"));
                            $responsable->setCargoR($request->get("cargo"));
                            $responsable->setNyAResolucion($request->get("nya"));
                            $responsable->setCedulaR($request->get("cedula"));
                            $responsable->setFechaInicioResolucion(date_create($request->get("fecha")));
                            $responsable->setControl($request->get("control"));
                        
                        }
                    }
                }

                if ($request->get("status") != "Esperando Por Concejo De Facultad") {

                    $responsable->setNyAE($this->getUser()->getNombreCorto());
                    $responsable->setFechaE(date_create());
                }
                

                $concurso->addResponsable($responsable);

                $em->flush();

                return new JsonResponse("S");
            }
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    public function cambiarFormatoFecha($fecha){

        $dia = substr($fecha, 0, 2);
        $mes = substr($fecha, 3, 2);
        $ano = substr($fecha, 6, 4);

        return $mes."/".$dia."/".$ano;
    }

    /**
     * @Route("/concursoOposicion/buscarJuradoAjax", name="buscarJuradoAjax")
     * @Method("POST")
     */
    public function buscarJuradoAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Jurado');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.id = :id and p.tipo = :cadena')
                ->setParameter('id', $request->get("id"))
                ->setParameter('cadena', 'Oposicion')
                ->getQuery();
             
            $jurados = $query->getResult();

            if (!$jurados) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaJurado($jurados,'nombre',$val, 1);
                $val = $this->asignarFilaJurado($jurados,'apellido',$val, 2);
                $val = $this->asignarFilaJurado($jurados,'areainvestigacion',$val, 3);
                $val = $this->asignarFilaJurado($jurados,'facultad',$val, 4);
                $val = $this->asignarFilaJurado($jurados,'universidad',$val, 5);
                $val = $this->asignarFilaJurado($jurados,'idusuarioasigna',$val, 6);
                $val = $this->asignarFilaJurado($jurados,'cedula',$val, 7);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    /**
     * @Route("/concursoOposicion/buscarJuradoSuplenteAjax", name="buscarJuradoSuplenteAjax")
     * @Method("POST")
     */
    public function buscarJuradoSuplenteAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Jurado');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.id = :id and p.tipo = :cadena')
                ->setParameter('id', $request->get("id"))
                ->setParameter('cadena', 'OposicionSuplentes')
                ->getQuery();
             
            $jurados = $query->getResult();

            if (!$jurados) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaJurado($jurados,'nombre',$val, 1);
                $val = $this->asignarFilaJurado($jurados,'apellido',$val, 2);
                $val = $this->asignarFilaJurado($jurados,'areainvestigacion',$val, 3);
                $val = $this->asignarFilaJurado($jurados,'facultad',$val, 4);
                $val = $this->asignarFilaJurado($jurados,'universidad',$val, 5);
                $val = $this->asignarFilaJurado($jurados,'idusuarioasigna',$val, 6);
                $val = $this->asignarFilaJurado($jurados,'cedula',$val, 7);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    /**
     * @Route("/concursoOposicion/borrarJuradoAjax", name="borrarJuradoAjax")
     * @Method("POST")
     */
    public function borrarJuradoAjaxAction(Request $request){

        if($request->isXmlHttpRequest())
        {
            $encontrado = false;

            foreach ($this->getUser()->getRoles() as $rol => $valor) {
                
                if ($valor == "Asuntos Profesorales")
                    $encontrado = true;
            }

            if ($encontrado){

                $em = $this->getDoctrine()->getManager();

                $jurado = $em->getRepository('ConcursosBundle:Jurado')->find($request->get("id"));

                $em->remove($jurado);

                $em->flush();
                
                return new JsonResponse("S");

            } else return new JsonResponse("N");             
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }


    /**
     * @Route("/concursoOposicion/modificarJuradoAjax", name="modificarJuradoAjax")
     * @Method("POST")
     */
    public function modificarJuradoAjaxAction(Request $request){

        if($request->isXmlHttpRequest())
        {
            $encontrado = false;

            foreach ($this->getUser()->getRoles() as $rol => $valor) {
                
                if ($valor == "Asuntos Profesorales")
                    $encontrado = true;
            }

            if ($encontrado){

                $em = $this->getDoctrine()->getManager();

                $jurado = $em->getRepository('ConcursosBundle:Jurado')->find($request->get("id"));

                if ($request->get("cedula") != null || $request->get("cedula") != "")
                    $jurado->setCedula($request->get("cedula"));

                if ($request->get("nombre") != null || $request->get("nombre") != "")
                    $jurado->setNombre($request->get("nombre"));

                if ($request->get("apellido") != null || $request->get("apellido") != "")
                    $jurado->setApellido($request->get("apellido"));

                if ($request->get("facultad") != null || $request->get("facultad") != "")
                    $jurado->setFacultad($request->get("facultad"));

                if ($request->get("universidad") != null || $request->get("universidad") != "")
                    $jurado->setUniversidad($request->get("universidad"));

                if ($request->get("area") != null || $request->get("area") != "")
                    $jurado->setAreaInvestigacion($request->get("area"));

                $jurado->setIdUsuarioAsigna($this->getUser()->getId());

                $em->flush();
                
                return new JsonResponse("S");

            } else return new JsonResponse("N");             
        }
        else
            throw $this->createNotFoundException('Error al devolver datos');
    }

    /**
     * @Route("/concursoOposicion/buscarCpecAjax", name="buscarCpecAjax")
     * @Method("POST")
     */
    public function buscarCpecAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Jurado');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.id = :id and p.tipo = :cadena')
                ->setParameter('id', $request->get("id"))
                ->setParameter('cadena', 'OposicionCpec')
                ->getQuery();
             
            $jurados = $query->getResult();

            if (!$jurados) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaJurado($jurados,'nombre',$val, 1);
                $val = $this->asignarFilaJurado($jurados,'apellido',$val, 2);
                $val = $this->asignarFilaJurado($jurados,'areainvestigacion',$val, 3);
                $val = $this->asignarFilaJurado($jurados,'facultad',$val, 4);
                $val = $this->asignarFilaJurado($jurados,'universidad',$val, 5);
                $val = $this->asignarFilaJurado($jurados,'idusuarioasigna',$val, 6);
                $val = $this->asignarFilaJurado($jurados,'cedula',$val, 7);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    /**
     * @Route("/concursoOposicion/buscarCpecSuplenteAjax", name="buscarCpecSuplenteAjax")
     * @Method("POST")
     */
    public function buscarCpecSuplenteAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Jurado');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.id = :id and p.tipo = :cadena')
                ->setParameter('id', $request->get("id"))
                ->setParameter('cadena', 'OposicionSuplenteCpec')
                ->getQuery();
             
            $jurados = $query->getResult();

            if (!$jurados) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaJurado($jurados,'nombre',$val, 1);
                $val = $this->asignarFilaJurado($jurados,'apellido',$val, 2);
                $val = $this->asignarFilaJurado($jurados,'areainvestigacion',$val, 3);
                $val = $this->asignarFilaJurado($jurados,'facultad',$val, 4);
                $val = $this->asignarFilaJurado($jurados,'universidad',$val, 5);
                $val = $this->asignarFilaJurado($jurados,'idusuarioasigna',$val, 6);
                $val = $this->asignarFilaJurado($jurados,'cedula',$val, 7);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    private function asignarFilaAspirante($object,$entidad,$val, $pos)
    {
        $i = 0;
        foreach($object as $value)
        {
           switch ($pos) {
               case 1: $val[$entidad][$i] = $value->getPrimerNombre(); break;
               case 2: $val[$entidad][$i] = $value->getSegundoNombre(); break;
               case 3: $val[$entidad][$i] = $value->getPrimerApellido(); break;
               case 4: $val[$entidad][$i] = $value->getSegundoApellido(); break;
               case 5: $val[$entidad][$i] = $value->getTelefono(); break;
               case 6: $val[$entidad][$i] = $value->getCorreo(); break;
               case 7: $val[$entidad][$i] = $value->getCedula(); break;
               case 8: $val[$entidad][$i] = $value->getUniversidadEgresado(); break;
               case 9: $val[$entidad][$i] = $value->getObservaciones(); break;

               default: $val[$entidad][$i] = $value->getId(); break;
           }
           $i++;
        }
        return $val;
    }

    /**
     * @Route("/concursoOposicion/listadoRecusacionAjax", name="listadoRecusacionAjax")
     * @Method("POST")
     */
    public function listadoRecusacionAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $repository = $this->getDoctrine()
                ->getRepository('ConcursoOposicionBundle:Recusacion');
             
            $query = $repository->createQueryBuilder('p')
                ->orderBy('p.id', 'DESC')
                ->getQuery();
             
            $recusacion = $query->getResult();

            if (!$recusacion) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaRec($recusacion,'id',$val, 0);
                $val = $this->asignarFilaRec($recusacion,'jurado',$val, 1);
                $val = $this->asignarFilaRec($recusacion,'aspirante',$val, 2);
                $val = $this->asignarFilaRec($recusacion,'fecha',$val, 3);
                $val = $this->asignarFilaRec($recusacion,'usuario',$val, 4);

                return new JsonResponse($val);
            }
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    private function asignarFilaRec($object,$entidad,$val, $pos)
    {
        $i = 0;
        foreach($object as $value)
        {
           switch ($pos) {
               case 1: $val[$entidad][$i] = $this->juradoAsigna($value->getJuradoId()); break;
               case 2: $val[$entidad][$i] = $this->aspiranteAsigna($value->getAspiranteId()); break;
               case 3: $val[$entidad][$i] = date_format($value->getFecha(), 'd-m-Y'); break;
               case 4: $val[$entidad][$i] = $this->usuarioAsigna($value->getUsuario()); break;

               default: $val[$entidad][$i] = $value->getId(); break;
           }
           $i++;
        }
        return $val;
    }

    private function juradoAsigna($id){

        if ($id == null)
            return "";
        else {

            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Jurado');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.id = :cadena')
                ->setParameter('cadena', $id)
                ->getQuery();
             
            $usuario = $query->getResult();

            if ($usuario == null) return "";
            else return $usuario[0]->getNombre()." ".$usuario[0]->getApellido();
        }
    }

    private function aspiranteAsigna($id){

        if ($id == null)
            return "";
        else {

            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Aspirante');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.id = :cadena')
                ->setParameter('cadena', $id)
                ->getQuery();
             
            $usuario = $query->getResult();

            if ($usuario == null) return "";
            else return $usuario[0]->getPrimerNombre()." ".$usuario[0]->getPrimerApellido();
        }
    }


    /**
     * @Route("/concursoOposicion/buscarRecusadoAjax", name="buscarRecusadoAjax")
     * @Method("POST")
     */
    public function buscarRecusadoAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $repository = $this->getDoctrine()
                ->getRepository('ConcursoOposicionBundle:Recusacion');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.id = :id')
                ->setParameter('id', $request->get("id"))
                ->getQuery();
             
            $recusacion = $query->getResult();

            if (!$recusacion) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaRec($recusacion,'id',$val, 0);
                $val = $this->asignarFilaRec($recusacion,'jurado',$val, 1);
                $val = $this->asignarFilaRec($recusacion,'aspirante',$val, 2);
                $val = $this->asignarFilaRec($recusacion,'fecha',$val, 3);
                $val = $this->asignarFilaRec($recusacion,'usuario',$val, 4);

                return new JsonResponse($val);
            }
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    /**
     * @Route("/concursoOposicion/borrarRecusacionAjax", name="borrarRecusacionAjax")
     * @Method("POST")
     */
    public function borrarRecusacionAjaxAction(Request $request){

        if($request->isXmlHttpRequest())
        {
            $encontrado = false;

            foreach ($this->getUser()->getRoles() as $rol => $valor) {
                
                if ($valor == "Asuntos Profesorales")
                    $encontrado = true;
            }

            if ($encontrado){

                $em = $this->getDoctrine()->getManager();

                $recusacion = $em->getRepository('ConcursoOposicionBundle:Recusacion')->find($request->get("id"));

                $em->remove($recusacion);

                $em->flush();
                
                return new JsonResponse("S");

            } else return new JsonResponse("N");             
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    /**
     * @Route("/concursoOposicion/listadoAspiranteAjax", name="listadoAspiranteAjax")
     * @Method("POST")
     */
    public function listadoAspiranteAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {        
            if ($request->get("resul") == "si"){

            	$query = $this->getDoctrine()
            	->getManager()
            	->createQuery("SELECT u
                                     FROM ConcursosBundle:Aspirante u, ConcursosBundle:Concurso c
                                        INNER JOIN  c.aspirantes r
                                     WHERE c.tipo = 'Oposicion' and c.id = :concurso and r = u.id")
                                     ->setParameter(':concurso', $request->get("concurso"));
            	
            	$recusacion = $query->getResult();
            	
            } else {
            	
            	$query = $this->getDoctrine()
            	->getManager()
            	->createQuery("SELECT u
                                     FROM ConcursosBundle:Aspirante u, ConcursosBundle:Concurso c
                                        INNER JOIN  c.aspirantes r
                                     WHERE c.tipo = 'Oposicion' and r = u.id");
            	 
            	$recusacion = $query->getResult();
            }

            if (!$recusacion) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaAsp($recusacion,'id',$val, 0);
                $val = $this->asignarFilaAsp($recusacion,'nombre1',$val, 1);
                $val = $this->asignarFilaAsp($recusacion,'nombre2',$val, 2);
                $val = $this->asignarFilaAsp($recusacion,'apellido1',$val, 3);
                $val = $this->asignarFilaAsp($recusacion,'apellido2',$val, 4);
                $val = $this->asignarFilaAsp($recusacion,'cedula',$val, 5);
                $val = $this->asignarFilaAsp($recusacion,'tlf1',$val, 6);
                $val = $this->asignarFilaAsp($recusacion,'tlf2',$val, 7);
                $val = $this->asignarFilaAsp($recusacion,'correo',$val, 8);
                $val = $this->asignarFilaAsp($recusacion,'universidad',$val, 9);
                $val = $this->asignarFilaAsp($recusacion,'titulo',$val, 10);
                $val = $this->asignarFilaAsp($recusacion,'anho',$val, 11);
                $val = $this->asignarFilaAsp($recusacion,'observacion',$val, 12);

                return new JsonResponse($val);
            }
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    private function asignarFilaAsp($object,$entidad,$val, $pos)
    {
        $i = 0;
        foreach($object as $value)
        {
           switch ($pos) {
               case 1: $val[$entidad][$i] = $value->getPrimerNombre(); break;
               case 2: $val[$entidad][$i] = $value->getSegundoNombre(); break;
               case 3: $val[$entidad][$i] = $value->getPrimerApellido(); break;
               case 4: $val[$entidad][$i] = $value->getSegundoApellido(); break;
               case 5: $val[$entidad][$i] = $value->getCedula(); break;
               case 6: $val[$entidad][$i] = $value->getTelefono(); break;
               case 7: $val[$entidad][$i] = $value->getTelefonoSecundario(); break;
               case 8: $val[$entidad][$i] = $value->getCorreo(); break;
               case 9: $val[$entidad][$i] = $value->getUniversidadEgresado(); break;
               case 10: $val[$entidad][$i] = $value->getDescripcionTituloUniv(); break;
               case 11: $val[$entidad][$i] = $value->getAnyoGraduacion(); break;
               case 12: $val[$entidad][$i] = $value->getObservaciones(); break;

               default: $val[$entidad][$i] = $value->getId(); break;
           }
           $i++;
        }
        return $val;
    }

    /**
     * @Route("/concursoOposicion/buscarAspiranteAjax", name="buscarAspiranteAjax")
     * @Method("POST")
     */
    public function buscarAspiranteAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Aspirante');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.id = :id')
                ->setParameter('id', $request->get("id"))
                ->getQuery();
             
            $jurados = $query->getResult();

            if (!$jurados) {
                 return new JsonResponse("N");
            }else
            {
                $val = $this->asignarFilaAsp($jurados,'id',$val, 0);
                $val = $this->asignarFilaAsp($jurados,'nombre1',$val, 1);
                $val = $this->asignarFilaAsp($jurados,'nombre2',$val, 2);
                $val = $this->asignarFilaAsp($jurados,'apellido1',$val, 3);
                $val = $this->asignarFilaAsp($jurados,'apellido2',$val, 4);
                $val = $this->asignarFilaAsp($jurados,'cedula',$val, 5);
                $val = $this->asignarFilaAsp($jurados,'tlf1',$val, 6);
                $val = $this->asignarFilaAsp($jurados,'tlf2',$val, 7);
                $val = $this->asignarFilaAsp($jurados,'correo',$val, 8);
                $val = $this->asignarFilaAsp($jurados,'universidad',$val, 9);
                $val = $this->asignarFilaAsp($jurados,'titulo',$val, 10);
                $val = $this->asignarFilaAsp($jurados,'anho',$val, 11);
                $val = $this->asignarFilaAsp($jurados,'observacion',$val, 12);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    /**
     * @Route("/concursoOposicion/borrarAspiranteAjax", name="borrarAspiranteAjax")
     * @Method("POST")
     */
    public function borrarAspiranteAjaxAction(Request $request){

        if($request->isXmlHttpRequest())
        {
            $encontrado = false;

            foreach ($this->getUser()->getRoles() as $rol => $valor) {
                
                if ($valor == "Asuntos Profesorales")
                    $encontrado = true;
            }

            if ($encontrado){

                $em = $this->getDoctrine()->getManager();

                $concurso = $em->getRepository('ConcursosBundle:Aspirante')->find($request->get("id"));

                $em->remove($concurso);

                $em->flush();
                
                return new JsonResponse("S");

            } else return new JsonResponse("N");             
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }

    /**
     * @Route("/concursoOposicion/modificarAspiranteAjax", name="modificarAspiranteAjax")
     * @Method("POST")
     */
    public function modificarAspiranteAjaxAction(Request $request){

        if($request->isXmlHttpRequest())
        {
            $encontrado = false;

            foreach ($this->getUser()->getRoles() as $rol => $valor) {
                
                if ($valor == "Asuntos Profesorales")
                    $encontrado = true;
            }

            if ($encontrado){

                $em = $this->getDoctrine()->getManager();

                $aspirante = $em->getRepository('ConcursosBundle:Aspirante')->find($request->get("id"));

                $aspirante->setTelefono($request->get("tlf"));
                $aspirante->setCorreo($request->get("correo"));

                $em->flush();
                
                return new JsonResponse("S");

            } else return new JsonResponse("N");             
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }
    
    /**
     * @Route("/concursoOposicion/listadoResultadoAjax", name="listadoResultadoAjax")
     * @Method("POST")
     */
    public function listadoResultadoAjaxAction(Request $request){
    
    	$val[][] = "";
    
    	if($request->isXmlHttpRequest())
    	{
    		    			 
    		$query = $this->getDoctrine()
    		->getManager()
    		->createQuery("SELECT u
                                     FROM ConcursosBundle:Resultado u
                                     WHERE u.cedulaAspirante = :cedula and u.idConcurso = :concurso")
    		->setParameter(':cedula', $request->get("cedula"))
    		->setParameter(':concurso', $request->get("concurso"));
    
    		$recusacion = $query->getResult();
    
    		if (!$recusacion) {
    			return new JsonResponse("N");
    		}else
    		{
    			$val = $this->asignarFilaResult($recusacion,'id',$val, 0);
    			$val = $this->asignarFilaResult($recusacion,'cedulaAspirante',$val, 1);
    			$val = $this->asignarFilaResult($recusacion,'idConcurso',$val, 2);
    			$val = $this->asignarFilaResult($recusacion,'nroPrueba',$val, 3);
    			$val = $this->asignarFilaResult($recusacion,'nota',$val, 4);
    			$val = $this->asignarFilaResult($recusacion,'notaEscrito',$val, 5);
    			$val = $this->asignarFilaResult($recusacion,'aptitud',$val, 6);
    			$val = $this->asignarFilaResult($recusacion,'psicologica',$val, 7);
    			$val = $this->asignarFilaResult($recusacion,'responsable',$val, 8);
    			$val = $this->asignarFilaResult($recusacion,'resultado',$val, 9);
    			$val = $this->asignarFilaResult($recusacion,'notaOral',$val, 10);
    
    			return new JsonResponse($val);
    		}
    	}
    	else
    		throw $this->createNotFoundException('Error al devolver datos');
    }
    
    private function asignarFilaResult($object,$entidad,$val, $pos)
    {
    	$i = 0;
    	foreach($object as $value)
    	{
    		switch ($pos) {
    			case 1: $val[$entidad][$i] = $value->getCedulaAspirante(); break;
    			case 2: $val[$entidad][$i] = $value->getIdConcurso(); break;
    			case 3: $val[$entidad][$i] = $value->getNroPrueba(); break;
    			case 4: $val[$entidad][$i] = $value->getNota(); break;
    			case 5: $val[$entidad][$i] = $value->getNotaEscrito(); break;
    			case 6: $val[$entidad][$i] = $value->getAptitud(); break;
    			case 7: $val[$entidad][$i] = $value->getPsicologica(); break;
    			case 8: $val[$entidad][$i] = $value->getResponsable(); break;
    			case 9: $val[$entidad][$i] = $value->getResultado(); break;
    			case 10: $val[$entidad][$i] = $value->getNotaOral(); break;
    
    			default: $val[$entidad][$i] = $value->getId(); break;
    		}
    		$i++;
    	}
    	return $val;
    }
    
    /**
     * @Route("/concursoOposicion/buscarUsuarioAjax", name="buscarUsuarioAjax")
     * @Method("POST")
     */
    public function buscarUsuarioAjaxAction(Request $request){
    
    	$val[][] = "";
    
    	if($request->isXmlHttpRequest())
    	{
    
    		$query = $this->getDoctrine()
    		->getManager()
    		->createQuery("SELECT u 
                   FROM AppBundle:Usuario u
                   WHERE u.id = :id")
                    ->setParameter(':id', $request->get("id"));
    
            $recusacion = $query->getResult();
    
            if (!$recusacion) {
               return new JsonResponse("No Data");
            } else {                                  
    
               return new JsonResponse($recusacion[0]->getPrimerNombre()." ".$recusacion[0]->getPrimerApellido());
            }
    	}
    	else
    		throw $this->createNotFoundException('Error al devolver datos');
    }
}
