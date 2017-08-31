<?php

namespace PreparadoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use TramiteBundle\Entity\Tramite;
use TramiteBundle\Entity\Recaudo;
use TramiteBundle\Entity\Transicion;
use ConcursosBundle\Entity\Concurso;
use ConcursosBundle\Entity\Jurado;
use AppBundle\Entity\Usuario;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/preparadores/apertura_concurso", name="apertura_concurso_index")
     */
    public function aperturaDeConcursoAction()
    {
        return $this->render('PreparadoresBundle::apertura_concurso.html.twig');
    }
    
    /**
     * @Route("/preparadores/apertura_concurso/solicitar", name="solicitar")
     */
    public function solicitarAction()
    {
        return $this->render('PreparadoresBundle::solicitar_concurso.html.twig');
    }
    
    /**
     * @Route("/preparadores/apertura_concurso/gestionar", name="gestionar_apertura")
     */
    public function gestionarAperturaAction()
    {
        return $this->render('PreparadoresBundle::gestionar_apertura.html.twig');
    }
    
    /**
     * @Route("/preparadores/concurso", name="concurso_index")
     */
    public function concursoAction()
    {
        return $this->render('PreparadoresBundle::concurso.html.twig');
    }
    
    /**
     * @Route("/preparadores/concurso/gestionar", name="gestionar_concurso")
     */
    public function gestionarConcursoAction()
    {
        return $this->render('PreparadoresBundle::gestionar_concurso.html.twig');
    }
    
    /**
     * @Route("/preparadores/renuncia_preparador", name="renuncia_preparador_index")
     */
    public function renunciaPreparadorAction()
    {
        return $this->render('PreparadoresBundle::renuncia_preparador.html.twig');
    }
    
    /**
     * @Route("/preparadores/registrar_solicitud", name="registrar_solicitud_ajax")
     */
    public function registrarSolicitudAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $falla = false;
            $idsJurados = array();
            $jurados = array("Coord","Ppal1","Ppal2","Supl1","Supl2");
            $juradosExistentes = array();

            $i = 0;
            $j = 0;
            foreach ($jurados as $jurado) {
                $result = $this->validarJurado($request->get($jurado), $jurado);
                if( $result == "JuradoNoExisteEnSistema"){
                    $falla=true;
                    $idsJurados[$j] = "#divJur".$jurado;
                    $j=$j+1;
                }
                /*else{
                   $juradosExistentes[$i] = $result;
                }*/
                $i=$i+1;
            }
            
            if (!$falla){
                $em = $this->getDoctrine()->getManager();
                
                $tipoTramite = $em->getRepository('TramiteBundle:TipoTramite')
                                  ->findOneBy(["id" => 5]);
            
                $tramiteSolicitudConcurso = new Tramite();
                $tramiteSolicitudConcurso->setTipoTramite($tipoTramite);
                $tramiteSolicitudConcurso->setObservacion("Solicitud de Concurso de Preparadores");
                $tramiteSolicitudConcurso->assignTo($this->getUser());
                
                $recaudos = array("AsigSol","NroPlz","ExOral","ExEsc","Coord","Ppal1","Ppal2","Supl1","Supl2");
                $i = 0;
                foreach ($recaudos as $value) {
                    $recaudo = new Recaudo();
                    $recaudo->setPath("");
                    $recaudo->setName($value);
                    $recaudo->setValor($request->get($value));
                    $recaudo->setTipoRecaudo(null);
                    $recaudo->setUsuario($this->getUser());

                    $tramiteSolicitudConcurso->addRecaudo($recaudo);
                    
                    $i=$i+1;
                }
                
                $estado = $em->getRepository('TramiteBundle:Estado')
                             ->findOneBy(["nombre" => 'Enviada']);
                
                $transicion = new Transicion();
                // $transicion->asignarA($tramiteSolicitudConcurso);
                $transicion->setEstado($estado);                  
                $transicion->setEstadoConsejo($estado);
                $transicion->setFecha(new \DateTime("now"));
                
                $tramiteSolicitudConcurso->addTransicion($transicion);
                
                $em->persist($tramiteSolicitudConcurso);
                
                $em->flush();
                
                /*$concurso = new Concurso();
                $concurso = $this->initialiceConcurso($concurso);
                $concurso->setAreaPostulacion($request->get("Asignatura"));
                $concurso->setNroVacantes($request->get("Plazas"));
                $concurso->setFechaInicio(new \DateTime("now"));
                $concurso->setIdUsuario($this->getUser()->getId());
                $concurso->setTipo("Preparadores");
                
                $em = $this->getDoctrine()->getManager();
                
                $i = 0;
                foreach ($juradosExistentes as $jurado) {
                    $juradoObjeto = $em->getRepository('ConcursosBundle:Jurado')
                                        ->findOneById($jurado);
                    $em->flush();
                    $concurso->addJurado($jurado);
                    //$jurado->setConcurso(intval($concurso->getId()));
                    $em->persist($jurado);
                    $i=$i+1;
                }
                
                $em->persist($concurso);
                $em->flush();*/
                
                return new JsonResponse(array(
                    'estado' => 'Insertado',
                    'mensaje' => 'Solicitud registrada exitosamente!.'
                ));
            }else{
               return new JsonResponse(array(
                    'estado' => 'NoExisteJurado',
                    'mensaje' => 'Jurado no está registrado en el Sistema.',
                    'jurados' => $idsJurados
                ));
            }
        }else
            throw $this->createNotFoundException('Error al solicitar datos');
    }
    
    public function validarJurado($jurado, $tipoJurado)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Usuario');
     
        $query = $repository->createQueryBuilder('Usuario')
            ->where('Usuario.cedula = :ciJurado')
            ->setParameter('ciJurado', $jurado)
            ->getQuery();
            
        $existeJurado = $query->getResult();
        
        if ($existeJurado == null){
            return ("JuradoNoExisteEnSistema");
        }else{
            return ("JuradoExisteEnSistema");
           /*switch ($tipoJurado) {
               case "Coord": $tipo = "Coordinador"; break;
               case "Ppal1": $tipo = "Principal"; break;
               case "Ppal2": $tipo = "Principal"; break;
               case "Supl1": $tipo = "Suplente"; break;
               case "Supl2": $tipo = "Suplente"; break;
           }
               
            $jurado = new Jurado();
            
            $jurado->setCedula($existeJurado[0]->getCedula());
            $jurado->setNombre($existeJurado[0]->getPrimerNombre()." ".$existeJurado[0]->getSegundoNombre());
            $jurado->setApellido($existeJurado[0]->getPrimerApellido()." ".$existeJurado[0]->getSegundoApellido());
            $jurado->setAreaInvestigacion("Algoritmo");
            //$jurado->setUniversidad($existeJurado->getUniversidad());
            $jurado->setTipo($tipo);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($jurado);
            $em->flush();
            
            return ($jurado);*/
        }
    }
    
    /**
     * @Route("/preparadores/listado_solicitud_concurso", name="listado_solicitud_concurso_ajax")
     */
    public function listadoSolicitudConcursoAjaxAction(Request $request)
    {
        $result[][] = "";

        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            
            $query = $em->createQuery('SELECT T, U
                                       FROM TramiteBundle:Tramite T
                                       JOIN T.usuario_id U
                                       WHERE T.tipo_tramite_id = :idTipoTramite
                                       ORDER BY T.id'
            )->setParameter('idTipoTramite', 5);
                     
            $tramites = $query->getArrayResult();

            if ($tramites == null) {
                 return new JsonResponse("NoHayConcursos");
            }else{
               // return new JsonResponse($tramites);
                $i = 0;
                foreach ($tramites as $tramite) {
                    $idTramite = $tramite["id"];
                    
                    $result = $this->asignarFilaSolicitudConcurso($tramite,'id',$result, 0, $i);
                    $result = $this->asignarFilaSolicitudConcurso($tramite,'idUsuario',$result, 1, $i);
                    
                    $query = $em->createQuery('SELECT R
                                               FROM TramiteBundle:Recaudo R
                                               WHERE R.tramite = :idTramite 
                                               AND R.name = :nameRecaudo'
                    )->setParameter('idTramite', $idTramite)
                     ->setParameter('nameRecaudo', 'AsigSol');
                    $recaudo = $query->getResult();
                    $result = $this->asignarFilaRecaudo($recaudo,'asignatura',$result, $i);
                    
                    $query = $em->createQuery('SELECT R
                                               FROM TramiteBundle:Recaudo R
                                               WHERE R.tramite = :idTramite 
                                               AND R.name = :nameRecaudo'
                    )->setParameter('idTramite', $idTramite)
                     ->setParameter('nameRecaudo', 'NroPlz');
                    $recaudo = $query->getResult();
                    $result = $this->asignarFilaRecaudo($recaudo,'plazas',$result, $i);

                    $query = $em->createQuery('SELECT E
                                               FROM TramiteBundle:Transicion T, TramiteBundle:Estado E
                                               WHERE T.tramite_id = :idTramite
                                               AND T.estado = E.id
                                               AND T.fecha = (SELECT MAX(T1.fecha)
                                                              FROM TramiteBundle:Transicion T1
                                                              WHERE T1.tramite_id = :idTramite)'
                    )->setParameter('idTramite', $idTramite);
                    $estado = $query->getResult();
                    $result = $this->asignarFilaEstadoTransicion($estado,'estado',$result, $i);
                    
                    $i++;
                    
                }
                return new JsonResponse($result);
            }
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }
    
    private function asignarFilaSolicitudConcurso($object, $nameField, $result, $case, $pos)
    {
        switch ($case) {
            case 0: $result[$nameField][$pos] = $object["id"]; break;
            case 1: $result[$nameField][$pos] = $object["usuario_id"]; break;
        }
        return $result;
    }
    
    private function asignarFilaRecaudo($object, $nameField, $result, $pos)
    {
        foreach($object as $value){
            $result[$nameField][$pos] = $value->getValor();
        }
        return $result;
    }
    
    private function asignarFilaEstadoTransicion($object, $nameField, $result, $pos)
    {
        foreach($object as $value){
            $result[$nameField][$pos] = $value->getNombre();
        }
        return $result;
    }
    
    /**
     * @Route("/preparadores/detalle_solicitud_concurso", name="detalle_solicitud_concurso_ajax")
     */
    public function detalleSolicitudConcursoAjaxAction(Request $request)
    {
        $result[][] = "";

        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            
            $idTramite = $request->get("id");
            
            $recaudos = array("AsigSol","NroPlz","ExOral","ExEsc","Coord","Ppal1","Ppal2","Supl1","Supl2");
            
            $i = 0;
            $falla = false;
            foreach ($recaudos as $value) {
                $query = $em->createQuery('SELECT R
                                       FROM TramiteBundle:Recaudo R
                                       WHERE R.tramite = :idTramite 
                                       AND R.name = :nameRecaudo'
                )->setParameter('idTramite', $idTramite)
                 ->setParameter('nameRecaudo', $value);
                $recaudo = $query->getResult();
                
                if($recaudo == null){
                    $falla = true;
                }else{
                    $result = $this->asignarFilaRecaudo($recaudo, $value, $result, $i);
                }
            }
                    
            if ($falla) {
                return new JsonResponse("FallaConsultaDetalleSolicitud");
            }else{
                return new JsonResponse($result);
            }
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }
    
    /**
     * @Route("/preparadores/agregar_recaudo_solicitud", name="agregar_recaudo_solicitud_ajax")
     */
    public function agregarRecaudoSolicitudAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $idTramite = $request->get("idTramite");
            $nombreRecaudo = $request->get("nombreRecaudo");
            
            $em = $this->getDoctrine()->getManager();
            
            $tramite = $em->getRepository('TramiteBundle:Tramite')
                              ->findOneBy(["id" => $idTramite]);
            
            $query = $em->createQuery('SELECT R
                                   FROM TramiteBundle:Recaudo R
                                   WHERE R.tramite = :idTramite 
                                   AND R.name = :nameRecaudo'
            )->setParameter('idTramite', $idTramite)
             ->setParameter('nameRecaudo', $nombreRecaudo);
            $recaudo = $query->getResult();
                         
            if($recaudo == null){
                $recaudo = new Recaudo();
                $recaudo->setPath("");
                $recaudo->setName($request->get("nombreRecaudo"));
                $recaudo->setValor($request->get("valorRecaudo"));
                $recaudo->setTipoRecaudo(null);
                $recaudo->setUsuario($this->getUser());
            }else{
                $recaudo->setValor($request->get("valorRecaudo"));
                $recaudo->setUsuario($this->getUser());
            }

            $tramite->addRecaudo($recaudo);
            
            $transicion = new Transicion();
            
            if($request->get("valorRecaudo")=="Si"){
                $estado = $em->getRepository('TramiteBundle:Estado')
                         ->findOneBy(["nombre" => 'En Proceso']);
                if($request->get("nombreRecaudo")=="AprobarPresupuesto"){
                    $transicion->setDoc_info("Usuario: aprobó el presupuesto para la solicitud.");
                }else if($request->get("nombreRecaudo")=="AprobarJurado"){
                    $transicion->setDoc_info("Usuario: aprobó el jurado de la solicitud.");
                }
            }else if($request->get("valorRecaudo")=="No"){
                if($request->get("nombreRecaudo")=="AprobarPresupuesto"){
                    $estado = $em->getRepository('TramiteBundle:Estado')
                         ->findOneBy(["nombre" => 'Negada']);
                    $transicion->setDoc_info("Usuario: no aprobó el presupuesto para la solicitud.");
                }else if($request->get("nombreRecaudo")=="AprobarJurado"){
                    $estado = $em->getRepository('TramiteBundle:Estado')
                         ->findOneBy(["nombre" => 'En Proceso']);
                    $transicion->setDoc_info("Usuario: no aprobó el jurado de la solicitud.");
                }
            }
            
            // $transicion = new Transicion();
            // $transicion->asignarA($tramite);
            $transicion->setFecha(new \DateTime("now"));
            $transicion->setEstado($estado);                  
            $transicion->setEstadoConsejo($estado);
            
            $tramite->addTransicion($transicion);
            
            $em->persist($tramite);
            
            $em->flush();
            
            return new JsonResponse(array(
                'estado' => 'Insertado',
                'mensaje' => 'Datos Enviados exitosamente!.'
            ));
        }else
            throw $this->createNotFoundException('Error al solicitar datos');
    }
    
    /**
     * @Route("/preparadores/validar_recaudo", name="validar_recaudo_ajax")
     */
    public function validarRecaudoAjaxAction(Request $request)
    {
        $result = "";
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            
            $idTramite = $request->get("idTramite");
            $nombreRecaudo = $request->get("nombreRecaudo");
            
            $query = $em->createQuery('SELECT R
                                   FROM TramiteBundle:Recaudo R
                                   WHERE R.tramite = :idTramite 
                                   AND R.name = :nameRecaudo'
            )->setParameter('idTramite', $idTramite)
             ->setParameter('nameRecaudo', $nombreRecaudo);
            $recaudo = $query->getResult();
            
            if($recaudo == null){
                return new JsonResponse(array(
                    'estado' => 'NoExisteRecaudo',
                    'valorRecaudo' => $result
                ));
            }else{
                foreach ($recaudo as $value) {
                    $result = $value->getValor();
                }
                return new JsonResponse(array(
                    'estado' => 'YaExisteRecaudo',
                    'valorRecaudo' => $result
                ));
            }
        }
        else
             throw $this->createNotFoundException('Error al devolver datos');
    }
}
