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
                $val = $this->asignarFilaConcursos($concurso,'usuario',$val, 1);
                $val = $this->asignarFilaConcursos($concurso,'observacion',$val, 2);
                $val = $this->asignarFilaConcursos($concurso,'vacantes',$val, 3);
                $val = $this->asignarFilaConcursos($concurso,'area',$val, 4);
                $val = $this->asignarFilaConcursos($concurso,'inicio',$val, 5);
                $val = $this->asignarFilaFechaRecepcion($concurso,'recepcion',$val);
                $val = $this->asignarFilaFechaPresentacion($concurso,'presentacion',$val);
            }
            return new JsonResponse($val);
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
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
               case 1: $val[$entidad][$i] = $value->getIdUsuario(); break;
               case 2: $val[$entidad][$i] = $value->getObservaciones(); break;
               case 3: $val[$entidad][$i] = $value->getNroVacantes();break;
               case 4: $val[$entidad][$i] = $value->getAreaPostulacion(); break;
               case 5: $val[$entidad][$i] = date_format($value->getFechaInicio(), 'd-m-Y'); break;

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
            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Jurado');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.tipo = :cadena')
                ->setParameter('cadena', 'Oposicion')
                ->orderBy('p.id', 'DESC')
                ->getQuery();
             
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
               case 6: $val[$entidad][$i] = $value->getIdUsuarioAsigna(); break;
               case 7: $val[$entidad][$i] = $value->getCedula(); break;

               default: $val[$entidad][$i] = $value->getId(); break;
           }
           $i++;
        }
        return $val;
    }

     /**
     * @Route("/concursoOposicion/listadoSuplentesAjax", name="listadoSuplentesAjax")
     * @Method("POST")
     */
    public function listadoSuplentesAjaxAction(Request $request){

        $val[][] = "";

        if($request->isXmlHttpRequest())
        {
            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Jurado');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.tipo = :cadena')
                ->setParameter('cadena', 'OposicionSuplentes')
                ->orderBy('p.id', 'DESC')
                ->getQuery();
             
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
            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Jurado');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.tipo = :cadena')
                ->setParameter('cadena', 'OposicionCpec')
                ->orderBy('p.id', 'DESC')
                ->getQuery();
             
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
            $repository = $this->getDoctrine()
                ->getRepository('ConcursosBundle:Jurado');
             
            $query = $repository->createQueryBuilder('p')
                ->where('p.tipo = :cadena')
                ->setParameter('cadena', 'OposicionSuplenteCpec')
                ->orderBy('p.id', 'DESC')
                ->getQuery();
             
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
}
