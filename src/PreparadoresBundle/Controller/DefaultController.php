<?php

namespace PreparadoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use TramiteBundle\Entity\Tramite;
use TramiteBundle\Entity\Recaudo;
use TramiteBundle\Entity\Transicion;
use ConcursosBundle\Entity\Concurso;
use ConcursosBundle\Entity\Aspirante;
use ConcursosBundle\Entity\Jurado;
use ConcursosBundle\Entity\Resultado;
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
     * @Route("/preparadores/concurso/aspirante/gestionar", name="gestionar_aspirante")
     */
    public function gestionarAspiranteAction()
    {
        return $this->render('PreparadoresBundle::gestionar_aspirante.html.twig');
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

                $estado = $em->getRepository('TramiteBundle:Estado')->findOneBy(["nombre" => 'Enviada']);
                
                $transicion = new Transicion();
                $transicion->setEstado($estado);                  
                $transicion->setEstadoConsejo($estado);
                $transicion->setFecha(new \DateTime("now"));
                $transicion->setDoc_info("Se ha iniciado el proceso de la solicitud.");
                
                $tramiteSolicitudConcurso->addTransicion($transicion);
                
                $em->persist($tramiteSolicitudConcurso);
                
                $em->flush();
                
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
                $i = 0;
                foreach ($tramites as $tramite) {
                    $idTramite = $tramite["id"];
                    
                    $result = $this->asignarFilaSolicitudConcurso($tramite,'id',$result, 0, $i);
                    // $result = $this->asignarFilaSolicitudConcurso($tramite,'idUsuario',$result, 1, $i);
                    
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
        }else
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
        }else
             throw $this->createNotFoundException('Error al devolver datos');
    }
    
    /**
     * @Route("/preparadores/agregar_recaudo_solicitud", name="agregar_recaudo_solicitud_ajax")
     */
    public function agregarRecaudoSolicitudAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $falla = false;
            $idsJurados = array();
            $divJurados = array("Coord","Ppal1","Ppal2","Supl1","Supl2");
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
            $queryRecaudo = $query->getResult();

            if($queryRecaudo == null){
                $recaudo = new Recaudo();
                $recaudo->setPath("");
                $recaudo->setName($request->get("nombreRecaudo"));
                $recaudo->setTipoRecaudo(null);
                $recaudo->setUsuario($this->getUser());
                if($request->get("nombreRecaudo") == "CambioJurado"){
                    $recaudo->setValor("Si");
                    $jurados = $request->get("valorRecaudo");
                    
                    $i = 0;
                    $j = 0;
                    foreach ($jurados as $jurado) {
                        $result = $this->validarJurado($jurado, $divJurados[$i]);
                        if( $result == "JuradoNoExisteEnSistema"){
                            $falla=true;
                            $idsJurados[$j] = "#divNewJur".$divJurados[$i];
                            $j=$j+1;
                        }
                        $i=$i+1;
                    }
                    
                }else{
                    $recaudo->setValor($request->get("valorRecaudo"));
                }
            }else{
                foreach ($queryRecaudo as $recaudo) {
                    $recaudo->setValor($request->get("valorRecaudo"));
                    $recaudo->setUsuario($this->getUser());
                }
            }
            
            if (!$falla){
                if($request->get("nombreRecaudo") == "CambioJurado"){
                    
                    $recaudos = $request->get("valorRecaudo");
                    /*RECORRO LOS JURADOS PARA ACTUALIZARLOS*/
                    $i = 0;
                    foreach ($recaudos as $racaudoAux) {
                        $query = $em->createQuery('SELECT R
                                               FROM TramiteBundle:Recaudo R
                                               WHERE R.tramite = :idTramite 
                                               AND R.name = :nameRecaudo'
                        )->setParameter('idTramite', $idTramite)
                         ->setParameter('nameRecaudo', $divJurados[$i]);
                        $queryResult = $query->getResult();
                        
                        foreach ($queryResult as $queryResultAux) {
                            $queryResultAux->setValor($racaudoAux);
                            $queryResultAux->setUsuario($this->getUser());
                        }
                        $i=$i+1;
                    }
                }

                $tramite->addRecaudo($recaudo);
                
                if($request->get("nombreRecaudo")!="ObservacionVeredicto"){
                    $entityEstado = $em->getRepository('TramiteBundle:Estado');
                    
                    $aprobada = $entityEstado->findOneBy(["nombre" => 'Aprobada']);
                    $enProceso = $entityEstado->findOneBy(["nombre" => 'En Proceso']);
                    $negada = $entityEstado->findOneBy(["nombre" => 'Negada']);
                    $finalizada = $entityEstado->findOneBy(["nombre" => 'Finalizada']);
                    
                    $transicion = new Transicion();
                    
                    if($request->get("valorRecaudo")=="Si"){
                        if($request->get("nombreRecaudo")=="Veredicto"){
                            $estado = $aprobada;
                            $transicion->setDoc_info("Aprobaron la solicitud.");
                        }else{
                            $estado = $enProceso;
                            if($request->get("nombreRecaudo")=="AprobarPresupuesto"){
                                $transicion->setDoc_info("Aprobaron el presupuesto para la solicitud.");
                            }else if($request->get("nombreRecaudo")=="AprobarJurado"){
                                $transicion->setDoc_info("Aprobaron el jurado de la solicitud.");
                            }
                        }
                    }else if($request->get("valorRecaudo")=="No"){
                        if($request->get("nombreRecaudo")=="AprobarPresupuesto" || $request->get("nombreRecaudo")=="Veredicto"){
                            $estado = $negada;
                            if($request->get("nombreRecaudo")=="AprobarPresupuesto"){
                                $transicion->setDoc_info("No aprobaron el presupuesto para la solicitud.");
                            }else{
                                $transicion->setDoc_info("No aprobaron la solicitud.");
                            }
                        }else if($request->get("nombreRecaudo")=="AprobarJurado"){
                            $estado = $enProceso;
                            $transicion->setDoc_info("No aprobaron el jurado de la solicitud.");
                        }
                    }else if($request->get("nombreRecaudo")=="CambioJurado"){
                        $estado = $enProceso;
                        $transicion->setDoc_info("Se cambió el jurado de la solicitud.");
                    }else if($request->get("nombreRecaudo")=="FechaRecepDoc"){
                        $estado = $finalizada;
                        $transicion->setDoc_info("Ha finalizado el proceso de la solicitud.");
                    }
                    
                    $transicion->setFecha(new \DateTime("now"));
                    $transicion->setEstado($estado);                  
                    $transicion->setEstadoConsejo($estado);
                    
                    $tramite->addTransicion($transicion);
                
                }
                
                $em->persist($tramite);
                
                $em->flush();
                
                if($request->get("nombreRecaudo")=="CambioJurado"){
                    return new JsonResponse(array(
                        'estado' => 'Insertado',
                        'mensaje' => 'Nuevo Jurado guardado exitosamente!.'
                    ));
                }else{
                    return new JsonResponse(array(
                        'estado' => 'Insertado',
                        'mensaje' => 'Datos Enviados exitosamente!.'
                    ));
                }
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
    
    public function validarRecaudo($idTramite,$nombreRecaudo)
    {
        $result = "";
        $em = $this->getDoctrine()->getManager();
        
        $query = $em->createQuery('SELECT R
                               FROM TramiteBundle:Recaudo R
                               WHERE R.tramite = :idTramite 
                               AND R.name = :nameRecaudo'
        )->setParameter('idTramite', $idTramite)
         ->setParameter('nameRecaudo', $nombreRecaudo);
        $recaudo = $query->getResult();
        
        if($recaudo == null){
            return ($result);
        }else{
            foreach ($recaudo as $value) {
                $result = $value->getValor();
            }
            return ($result);
        }
    }
    
    /**
     * @Route("/preparadores/validar_accion_solicitud", name="validar_accion_solicitud_ajax")
     */
    public function validarAccionSolicitudAjaxAction(Request $request)
    {
        $respuesta = "";
        $valorRecaudo = array();
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            
            $idTramite = $request->get("idTramite");
            $nombresRecaudos = $request->get("nombresRecaudos");
            
            $i=0;
            foreach ($nombresRecaudos as $nombreRecaudo) {
                $result = $this->validarRecaudo($idTramite,$nombreRecaudo);
                $valorRecaudo[$i] = $result;
                $i=$i+1;
            }
            
            /***
                "AprobarPresupuesto" => 0
                "AprobarJurado" => 1
                "Veredicto" => 2
                "FechaRecepDoc" => 3
                "CambioJurado" => 4
            ***/
            
            if($valorRecaudo[0] == ""){
                $respuesta = "#divAprobarPresupuesto";
            }elseif($valorRecaudo[0] == "Si"){
                if($valorRecaudo[1] == ""){
                    $respuesta = "#divAprobarJurado";
                }elseif($valorRecaudo[1] == "Si"){
                    if($valorRecaudo[2] == ""){
                        $respuesta = "#divVeredicto";
                    }elseif($valorRecaudo[2] == "Si"){
                        if($valorRecaudo[3] == ""){
                            $respuesta = "#divFechaRecepDoc";
                        }
                    }
                }elseif($valorRecaudo[1] == "No"){
                    if($valorRecaudo[4] == ""){
                        $respuesta = "#divCambiarJurado";
                    }elseif($valorRecaudo[2] == ""){
                        $respuesta = "#divVeredicto";
                    }elseif($valorRecaudo[2] == "Si"){
                        if($valorRecaudo[3] == ""){
                            $respuesta = "#divFechaRecepDoc";
                        }
                    }
                }
            }
            
            return new JsonResponse($respuesta);
        }else
            throw $this->createNotFoundException('Error al devolver datos');
    }
    
    /**
     * @Route("/preparadores/registrar_concurso", name="registrar_concurso_ajax")
     */
    public function registrarConcursoAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
        
            $em = $this->getDoctrine()->getManager();
            
            $idTramite = $request->get("idTramite");
            
            $recaudosConcurso = array("AsigSol","NroPlz","ExOral","ExEsc","FechaRecepDoc");
            
            $concurso = new Concurso();
            $concurso->setFechaInicio(new \DateTime("now"));
            $concurso->setIdUsuario($this->getUser()->getId());
            $concurso->setTipo("Preparadores");
            
            $i = 0;
            foreach ($recaudosConcurso as $concursoAux) {
                $query = $em->createQuery('SELECT R
                                           FROM TramiteBundle:Recaudo R
                                           WHERE R.tramite = :idTramite 
                                           AND R.name = :nameRecaudo'
                )->setParameter('idTramite', $idTramite)
                 ->setParameter('nameRecaudo', $concursoAux);
                $resultRecaudo = $query->getResult();
                
                foreach ($resultRecaudo as $result) {
                    switch ($i) {
                        case 0: $concurso->setAreaPostulacion($result->getValor()); break;
                        case 1: $concurso->setNroVacantes($result->getValor()); break;
                        case 2: $concurso->setTemaExOral($result->getValor()); break;
                        case 3: $concurso->setTemaExEscrito($result->getValor()); break;
                        case 4: $concurso->setFechaRecepDoc(new \DateTime($result->getValor())); break;
                    }
                }
                $i=$i+1;
            }
            
            $recaudosJurado = array("Coord","Ppal1","Ppal2","Supl1","Supl2");
            
            $i = 0;
            $tipoJurado ="";
            foreach ($recaudosJurado as $juradoAux) {
                $query = $em->createQuery('SELECT U
                                           FROM TramiteBundle:Recaudo R, AppBundle:Usuario U
                                           WHERE R.tramite = :idTramite 
                                           AND R.name = :nameRecaudo
                                           AND R.valor = U.cedula'
                )->setParameter('idTramite', $idTramite)
                 ->setParameter('nameRecaudo', $juradoAux);
                $resultRecaudo = $query->getResult();
                
                switch ($i) {
                    case 0: $tipoJurado = "Coordinador"; break;
                    case 1: $tipoJurado = "Principal"; break;
                    case 2: $tipoJurado = "Principal"; break;
                    case 3: $tipoJurado = "Suplente"; break;
                    case 4: $tipoJurado = "Suplente"; break;
                }
                
                foreach ($resultRecaudo as $result) {
                    
                    $query = $em->createQuery('SELECT J
                                               FROM ConcursosBundle:Jurado J
                                               WHERE J.cedula = :cedulaJurado 
                                               AND J.tipo = :tipoJurado'
                    )->setParameter('cedulaJurado', $result->getCedula())
                     ->setParameter('tipoJurado', $tipoJurado);
                    $existeJurado = $query->getResult();
                    
                    if($existeJurado == null){
                        $jurado = new Jurado();
                        $jurado->setNombre($result->getPrimerNombre()." ".$result->getSegundoNombre());
                        $jurado->setApellido($result->getPrimerApellido()." ".$result->getSegundoApellido());
                        $jurado->setCedula($result->getCedula());
                        $jurado->setTipo($tipoJurado);
                        $em->persist($jurado);
                        $em->flush();
                    }else{
                        foreach ($existeJurado as $juradoExistente) {
                            $jurado = $juradoExistente;
                        }
                    }
                    $concurso->addJurado($jurado);
                }
                $i=$i+1;
            }
            
            $tipoTramite = $em->getRepository('TramiteBundle:TipoTramite')->findOneBy(["id" => 9]);
            $estado = $em->getRepository('TramiteBundle:Estado')->findOneBy(["nombre" => 'Enviada']);
            
            $concurso->setTipoTramite($tipoTramite);
            $concurso->setObservacion("Concurso de Preparadores");
            $concurso->assignTo($this->getUser());
            
            $transicion = new Transicion();
            $transicion->setEstado($estado);                  
            $transicion->setEstadoConsejo($estado);
            $transicion->setFecha(new \DateTime("now"));
            $transicion->setDoc_info("Se ha iniciado el proceso del Concurso.");
            
            $concurso->addTransicion($transicion);
            
            $em->persist($concurso);
            
            $em->flush();
            
            return new JsonResponse(array(
                'estado' => 'Insertado',
                'mensaje' => 'Datos Enviados exitosamente!.'
            ));
        }else
            throw $this->createNotFoundException('Error al solicitar datos');
    }
    
    /**
     * @Route("/preparadores/listado_concurso", name="listado_concurso_ajax")
     */
    public function listadoConcursoAjaxAction(Request $request)
    {
        $result[][] = "";

        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            
            $query = $em->createQuery('SELECT T, U
                                       FROM TramiteBundle:Tramite T
                                       JOIN T.usuario_id U
                                       WHERE T.tipo_tramite_id = :idTipoTramite
                                       ORDER BY T.id'
            )->setParameter('idTipoTramite', 9);
                     
            $tramites = $query->getArrayResult();

            if ($tramites == null) {
                 return new JsonResponse("NoHayConcursos");
            }else{
                $i = 0;
                foreach ($tramites as $tramite) {
                    $idTramite = $tramite["id"];
                    
                    $result = $this->asignarFilaSolicitudConcurso($tramite,'id',$result, 0, $i);
                    // $result = $this->asignarFilaSolicitudConcurso($tramite,'idUsuario',$result, 1, $i);
                    
                    $query = $em->createQuery('SELECT C
                                               FROM ConcursosBundle:Concurso C
                                               WHERE C.id = :idTramite'
                    )->setParameter('idTramite', $idTramite);
                    
                    $concurso = $query->getResult();
                    
                    $result = $this->asignarFilaConcurso($concurso,'asignatura',$result, 0, $i);

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
        }else
             throw $this->createNotFoundException('Error al devolver datos');
    }
    
    private function asignarFilaConcurso($object, $nameField, $result, $case, $pos)
    {
        foreach($object as $value){
            switch ($case) {
                case 0: $result[$nameField][$pos] = $value->getAreaPostulacion(); break;
                case 1: $result[$nameField][$pos] = $value->getNroVacantes(); break;
                case 2: $result[$nameField][$pos] = $value->getTemaExOral(); break;
                case 3: $result[$nameField][$pos] = $value->getTemaExEscrito(); break;
                case 4: $result[$nameField][$pos] = $value->getFechaRecepDoc(); break;
            }
        }
        return $result;
    }
    
    private function asignarFilaJurado($object, $nameField, $result, $pos)
    {
        $result[$nameField][$pos] = $object->getCedula();
        return $result;
    }
    
    private function asignarFilaAspirante($object, $nameField, $result, $case, $pos)
    {
        switch ($case) {
            case 0: $result[$nameField][$pos] = $object->getId(); break;
            case 1: $result[$nameField][$pos] = $object->getCedula(); break;
            case 2: $result[$nameField][$pos] = $object->getNombreCompleto(); break;
            case 3: $result[$nameField][$pos] = $object->getCorreo(); break;
            case 4: $result[$nameField][$pos] = $object->getTelefono(); break;
            case 5: $result[$nameField][$pos] = $object->getTelefonoSecundario(); break;
            case 6: $result[$nameField][$pos] = $object->getPromedioAcademico(); break;
            case 7: $result[$nameField][$pos] = $object->getNotaIntento1(); break;
            case 8: $result[$nameField][$pos] = $object->getNotaIntento2(); break;
            case 9: $result[$nameField][$pos] = $object->getComunicacionEscritaUrl(); break;
            case 10: $result[$nameField][$pos] = $object->getCartaConductaUrl(); break;
            case 11: $result[$nameField][$pos] = $object->getReporteNotaUrl(); break;
        }
        return $result;
    }
    
    /**
     * @Route("/preparadores/detalle_concurso", name="detalle_concurso_ajax")
     */
    public function detalleConcursoAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $result[][] = "";
            $falla = false;
            
            $em = $this->getDoctrine()->getManager();
            
            $idTramite = $request->get("id");
            
            $query = $em->createQuery('SELECT C
                                       FROM ConcursosBundle:Concurso C
                                       WHERE C.id = :idTramite'
            )->setParameter('idTramite', $idTramite);
            
            $concurso = $query->getResult();
            
            if($concurso == null){
                $falla=true;
            }else{
                $i = 0;
                
                $result = $this->asignarFilaConcurso($concurso,'asignatura',$result, 0, $i);
                $result = $this->asignarFilaConcurso($concurso,'vacantes',$result, 1, $i);
                $result = $this->asignarFilaConcurso($concurso,'exOral',$result, 2, $i);
                $result = $this->asignarFilaConcurso($concurso,'exEscrito',$result, 3, $i);
                $result = $this->asignarFilaConcurso($concurso,'fechaRecepDoc',$result, 4, $i);
                
                foreach ($concurso as $value) {
                    $aspirantes = $value->getAspirantes();
                    $a=0;
                    foreach ($aspirantes as $aspirante) {
                        $result = $this->asignarFilaAspirante($aspirante, 'idAspirante', $result, 0, $a);
                        $result = $this->asignarFilaAspirante($aspirante, 'cedula', $result, 1, $a);
                        $result = $this->asignarFilaAspirante($aspirante, 'nombreCompleto', $result, 2, $a);
                        $result = $this->asignarFilaAspirante($aspirante, 'correo', $result, 3, $a);
                        $result = $this->asignarFilaAspirante($aspirante, 'telefono', $result, 4, $a);
                        $a=$a+1;
                    }
                    if($a == 0){
                        $result['idAspirante'][$i] = $a;
                    }
                    
                    $jurados = $value->getJurados();
                    
                     $s = 0;
                     $p = 0;
                     $j = 0;
                    foreach ($jurados as $jurado) {
                        if($jurado->getTipo()=="Coordinador"){
                            $result = $this->asignarFilaJurado($jurado, $jurado->getTipo(), $result, $i);
                        }elseif($jurado->getTipo()=="Principal"){
                            $result = $this->asignarFilaJurado($jurado, $jurado->getTipo(), $result, $p);
                            $p=$p+1;
                        }elseif($jurado->getTipo()=="Suplente"){
                            $result = $this->asignarFilaJurado($jurado, $jurado->getTipo(), $result, $s);
                            $s=$s+1;
                        }
                        $j=$j+1;
                    }
                }               
            }
            if ($falla) {
                return new JsonResponse("FallaConsultaDetalleConcurso");
            }else{
                return new JsonResponse($result);
            }
        }else
             throw $this->createNotFoundException('Error al devolver datos');
    }
    
    /**
     * @Route("/preparadores/agregar_recaudo_concurso", name="agregar_recaudo_concurso_ajax")
     */
    public function agregarRecaudoConcursoAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $falla = false;
            $idsJurados = array();
            $divJurados = array("Coord","Ppal1","Ppal2","Supl1","Supl2");
            $idTramite = $request->get("idTramite");
            $nombreRecaudo = $request->get("nombreRecaudo");
            
            $em = $this->getDoctrine()->getManager();
            
            $concurso = $em->getRepository('ConcursosBundle:Concurso')
                              ->findOneBy(["id" => $idTramite]);
                              
            $tramite = $em->getRepository('TramiteBundle:Tramite')
                              ->findOneBy(["id" => $idTramite]);
            
            $query = $em->createQuery('SELECT R
                                   FROM TramiteBundle:Recaudo R
                                   WHERE R.tramite = :idTramite 
                                   AND R.name = :nameRecaudo'
            )->setParameter('idTramite', $idTramite)
             ->setParameter('nameRecaudo', $nombreRecaudo);
            $queryRecaudo = $query->getResult();

            if($queryRecaudo == null){
                $recaudo = new Recaudo();
                $recaudo->setPath("");
                $recaudo->setName($request->get("nombreRecaudo"));
                // if($request->get("nombreRecaudo") == "CambioJurado"){
                //     $recaudo->setValor("Si");
                //     $jurados = $request->get("valorRecaudo");
                    
                //     $i = 0;
                //     $j = 0;
                //     foreach ($jurados as $jurado) {
                //         $result = $this->validarJurado($jurado, $divJurados[$i]);
                //         if( $result == "JuradoNoExisteEnSistema"){
                //             $falla=true;
                //             $idsJurados[$j] = "#divNewJur".$divJurados[$i];
                //             $j=$j+1;
                //         }
                //         $i=$i+1;
                //     }
                    
                // }else{
                    $recaudo->setValor($request->get("valorRecaudo"));
                // }
                $recaudo->setTipoRecaudo(null);
                $recaudo->setUsuario($this->getUser());
            }else{
                foreach ($queryRecaudo as $recaudo) {
                    $recaudo->setValor($request->get("valorRecaudo"));
                    $recaudo->setUsuario($this->getUser());
                }
            }
            
            if (!$falla){
                // if($request->get("nombreRecaudo") == "CambioJurado"){
                    
                //     $recaudos = $request->get("valorRecaudo");
                //     /*RECORRO LOS JURADOS PARA ACTUALIZARLOS*/
                //     $i = 0;
                //     foreach ($recaudos as $racaudoAux) {
                //         $query = $em->createQuery('SELECT R
                //                               FROM TramiteBundle:Recaudo R
                //                               WHERE R.tramite = :idTramite 
                //                               AND R.name = :nameRecaudo'
                //         )->setParameter('idTramite', $idTramite)
                //          ->setParameter('nameRecaudo', $divJurados[$i]);
                //         $queryResult = $query->getResult();
                        
                //         foreach ($queryResult as $queryResultAux) {
                //             $queryResultAux->setValor($racaudoAux);
                //             $queryResultAux->setUsuario($this->getUser());
                //         }
                //         $i=$i+1;
                //     }
                // }

                $tramite->addRecaudo($recaudo);
                
                // if($request->get("nombreRecaudo")!="ObservacionVeredicto"){
                    $entityEstado = $em->getRepository('TramiteBundle:Estado');
                    
                    $aprobada = $entityEstado->findOneBy(["nombre" => 'Aprobada']);
                    $enProceso = $entityEstado->findOneBy(["nombre" => 'En Proceso']);
                    $negada = $entityEstado->findOneBy(["nombre" => 'Negada']);
                    $finalizada = $entityEstado->findOneBy(["nombre" => 'Finalizada']);
                    
                    $transicion = new Transicion();
                    
                    if($request->get("nombreRecaudo")=="CausalDesierto"){
                        $estado = $finalizada;
                        $transicion->setDoc_info("Declarado Concurso Desierto.");
                    }else if($request->get("nombreRecaudo")=="FechaEvaluacion"){
                        $estado = $enProceso;
                        $transicion->setDoc_info("Se registro la fecha de Presentacion.");
                        $concurso->setFechaPresentacion(new \DateTime($recaudo->getValor()));
                    }
                    
                    // if($request->get("valorRecaudo")=="Si"){
                    //     if($request->get("nombreRecaudo")=="Veredicto"){
                    //         $estado = $aprobada;
                    //         $transicion->setDoc_info("Aprobaron la solicitud.");
                    //     }else{
                    //         $estado = $enProceso;
                    //         if($request->get("nombreRecaudo")=="AprobarPresupuesto"){
                    //             $transicion->setDoc_info("Aprobaron el presupuesto para la solicitud.");
                    //         }else if($request->get("nombreRecaudo")=="AprobarJurado"){
                    //             $transicion->setDoc_info("Aprobaron el jurado de la solicitud.");
                    //         }
                    //     }
                    // }else if($request->get("valorRecaudo")=="No"){
                    //     if($request->get("nombreRecaudo")=="AprobarPresupuesto" || $request->get("nombreRecaudo")=="Veredicto"){
                    //         $estado = $negada;
                    //         if($request->get("nombreRecaudo")=="AprobarPresupuesto"){
                    //             $transicion->setDoc_info("No aprobaron el presupuesto para la solicitud.");
                    //         }else{
                    //             $transicion->setDoc_info("No aprobaron la solicitud.");
                    //         }
                    //     }else if($request->get("nombreRecaudo")=="AprobarJurado"){
                    //         $estado = $enProceso;
                    //         $transicion->setDoc_info("No aprobaron el jurado de la solicitud.");
                    //     }
                    // }else if($request->get("nombreRecaudo")=="CambioJurado"){
                    //     $estado = $enProceso;
                    //     $transicion->setDoc_info("Se cambió el jurado de la solicitud.");
                    // }else if($request->get("nombreRecaudo")=="FechaRecepDoc"){
                    //     $estado = $finalizada;
                    //     $transicion->setDoc_info("Ha finalizado el proceso de la solicitud.");
                    // }
                    
                    $transicion->setFecha(new \DateTime("now"));
                    $transicion->setEstado($estado);                  
                    $transicion->setEstadoConsejo($estado);
                    
                    $tramite->addTransicion($transicion);
                
                // }
                
                $em->persist($tramite);
                
                $em->flush();
                
                // if($request->get("nombreRecaudo")=="CambioJurado"){
                //     return new JsonResponse(array(
                //         'estado' => 'Insertado',
                //         'mensaje' => 'Nuevo Jurado guardado exitosamente!.'
                //     ));
                // }else{
                    return new JsonResponse(array(
                        'estado' => 'Insertado',
                        'mensaje' => 'Datos Enviados exitosamente!.'
                    ));
                // }
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
    
    /**
     * @Route("/preparadores/registrar_aspirante", name="registrar_aspirante_ajax")
     */
    public function registrarAspiranteAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $concurso = $em->getRepository('ConcursosBundle:Concurso')
                              ->findOneBy(["id" => $request->get('IdConcurso')]);
                              
        $newAspirante = new Aspirante();
        
        $identificadorAspirante= $request->get('PrimerNombre')."_".$request->get('PrimerApellido')."_".$request->get('Cedula')."_".$newAspirante->getId();
        
        $newAspirante->setCedula($request->get('Cedula'));
        $newAspirante->setCorreo($request->get('Correo'));
        $newAspirante->setPrimerNombre($request->get('PrimerNombre'));
        $newAspirante->setSegundoNombre($request->get('SegundoNombre'));
        $newAspirante->setPrimerApellido($request->get('PrimerApellido'));
        $newAspirante->setSegundoApellido($request->get('SegundoApellido'));
        $telefono=$request->get('TelefonoArea').$request->get('TelefonoNumero');
        $newAspirante->setTelefono($telefono);
        $newAspirante->setPromedioAcademico($request->get('Promedio'));
        $newAspirante->setNotaIntento1($request->get('Nota1'));
        if($request->get('TelefonoSecundarioArea') != "" && $request->get('TelefonoSecundarioNumero') != ""){
            $telefonoSecundario=$request->get('TelefonoSecundarioArea').$request->get('TelefonoSecundarioNumero');
            $newAspirante->setTelefonoSecundario($telefonoSecundario);
        }
        if($request->get('Nota2') != ""){
            $newAspirante->setNotaIntento2($request->get('Nota2'));
        }
        
        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/aspirantes/comunicacionEscrita/';
        $dir_subida = $dir_subida."comunicacion_escrita_".$identificadorAspirante.".pdf";
        
        $newAspirante->setComunicacionEscritaUrl($dir_subida);
        
        if(move_uploaded_file($_FILES['input']['tmp_name'][0], $dir_subida)) {
            $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/aspirantes/cartaConducta/';
            $dir_subida = $dir_subida."carta_conducta_".$identificadorAspirante.".pdf";
            
            $newAspirante->setCartaConductaUrl($dir_subida);
            
            if(move_uploaded_file($_FILES['input']['tmp_name'][1], $dir_subida)) {
                $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/aspirantes/reporteNota/';
                $dir_subida = $dir_subida."reporte_nota_".$identificadorAspirante.".pdf";
                
                $newAspirante->setReporteNotaUrl($dir_subida);
                
                if(move_uploaded_file($_FILES['input']['tmp_name'][2], $dir_subida)) {
                    $concurso->addAspirante($newAspirante);
                    $em->persist($concurso);
                    $em->flush();
                    return new RedirectResponse($this->generateUrl('gestionar_concurso',array('id' => $request->get('IdConcurso'), 'state' => 'success')));
                }else{
                    return new RedirectResponse($this->generateUrl('gestionar_concurso',array('id' => $request->get('IdConcurso'), 'state' => 'error')));
                }
            }else{
                return new RedirectResponse($this->generateUrl('gestionar_concurso',array('id' => $request->get('IdConcurso'), 'state' => 'error')));
            }
        }else{
            return new RedirectResponse($this->generateUrl('gestionar_concurso',array('id' => $request->get('IdConcurso'), 'state' => 'error')));
        }
    }
    
    /**
     * @Route("/preparadores/detalle_aspirante", name="detalle_aspirante_ajax")
     */
    public function detalleAspiranteAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $result[][] = "";
            $falla = false;
            
            $em = $this->getDoctrine()->getManager();
            
            $idAspirante = $request->get("id");
            
            $query = $em->createQuery('SELECT A
                                       FROM ConcursosBundle:Aspirante A
                                       WHERE A.id = :idAspirante'
            )->setParameter('idAspirante', $idAspirante);
            
            $aspirante = $query->getResult();
            
            if($aspirante == null){
                $falla = true;
            }else{
                $a=0;
                
                foreach ($aspirante as $value) {
                    $result = $this->asignarFilaAspirante($value, 'idAspirante', $result, 0, $a);
                    $result = $this->asignarFilaAspirante($value, 'cedula', $result, 1, $a);
                    $result = $this->asignarFilaAspirante($value, 'nombreCompleto', $result, 2, $a);
                    $result = $this->asignarFilaAspirante($value, 'correo', $result, 3, $a);
                    $result = $this->asignarFilaAspirante($value, 'telefono', $result, 4, $a);
                    $result = $this->asignarFilaAspirante($value, 'telefonoSecundario', $result, 5, $a);
                    $result = $this->asignarFilaAspirante($value, 'promedioAcademico', $result, 6, $a);
                    $result = $this->asignarFilaAspirante($value, 'notaIntento1', $result, 7, $a);
                    $result = $this->asignarFilaAspirante($value, 'notaIntento2', $result, 8, $a);
                    $result = $this->asignarFilaAspirante($value, 'comunicacionEscritaUrl', $result, 9, $a);
                    $result = $this->asignarFilaAspirante($value, 'cartaConductaUrl', $result, 10, $a);
                    $result = $this->asignarFilaAspirante($value, 'reporteNotaUrl', $result, 11, $a);
                    $a=$a+1;
                }
            }
            if ($falla) {
                return new JsonResponse("FallaConsultaDetalleAspirante");
            }else{
                return new JsonResponse($result);
            }
        }else
             throw $this->createNotFoundException('Error al devolver datos');
    }
    
        /**
     * @Route("/preparadores/agregar_datos_aspirante", name="agregar_datos_aspirante_ajax")
     */
    public function agregarDatosAspiranteAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $falla = false;
            $idsJurados = array();
            $divJurados = array("Coord","Ppal1","Ppal2","Supl1","Supl2");
            
            $idConcurso = $request->get("idTramite");
            $idAspirante = $request->get("idAspirante");
            $nombreDato = $request->get("nombreDato");
            $valorDato = $request->get("valorDato");
            
            $em = $this->getDoctrine()->getManager();
            
            $concurso = $em->getRepository('ConcursosBundle:Concurso')
                              ->findOneBy(["id" => $idConcurso]);
                              
            $aspirante = $em->getRepository('ConcursosBundle:Aspirante')
                              ->findOneBy(["id" => $idTramite]);
                              
            $query = $em->createQuery('SELECT R
                                   FROM TramiteBundle:Recaudo R
                                   WHERE R.tramite = :idConcurso 
                                   AND R.name = :nombreDato'
            )->setParameter('idConcurso', $idConcurso)
             ->setParameter('nombreDato', $nombreDato);
            $queryRecaudo = $query->getResult();

            if($queryRecaudo == null){
                if($nombreDato != "MotivosExoneracion" && $nombreDato != "Notas"){
                    $recaudo = new Recaudo();
                    $recaudo->setPath("");
                    $recaudo->setName($nombreDato);
                    $recaudo->setValor(1);
                    $recaudo->setTipoRecaudo(null);
                    $recaudo->setUsuario($this->getUser());
                }elseif($nombreDato == "Notas"){
                    $resultado = new Resultado();
                    $i = 0;
                    foreach ($valorDato as $nota) {
                        switch ($case) {
                            case 0: $aspirante->set = $object->getId(); break;
                            case 1: $result[$nameField][$pos] = $object->getCedula(); break;
                        }
                        
                        
                        $query = $em->createQuery('SELECT R
                                              FROM TramiteBundle:Recaudo R
                                              WHERE R.tramite = :idTramite 
                                              AND R.name = :nameRecaudo'
                        )->setParameter('idTramite', $idTramite)
                         ->setParameter('nameRecaudo', $divJurados[$i]);
                        $queryResult = $query->getResult();
                        
                        foreach ($queryResult as $queryResultAux) {
                            $queryResultAux->setValor($racaudoAux);
                            $queryResultAux->setUsuario($this->getUser());
                        }
                        $i=$i+1;
                    }
                    
                    
                }
                
            }else{
                foreach ($queryRecaudo as $recaudo) {
                    $recaudo->setValor($valorDato);
                    $recaudo->setUsuario($this->getUser());
                }
            }
            
            
            
            if (!$falla){
                // if($request->get("nombreRecaudo") == "CambioJurado"){
                    
                //     $recaudos = $request->get("valorRecaudo");
                //     /*RECORRO LOS JURADOS PARA ACTUALIZARLOS*/
                //     $i = 0;
                //     foreach ($recaudos as $racaudoAux) {
                //         $query = $em->createQuery('SELECT R
                //                               FROM TramiteBundle:Recaudo R
                //                               WHERE R.tramite = :idTramite 
                //                               AND R.name = :nameRecaudo'
                //         )->setParameter('idTramite', $idTramite)
                //          ->setParameter('nameRecaudo', $divJurados[$i]);
                //         $queryResult = $query->getResult();
                        
                //         foreach ($queryResult as $queryResultAux) {
                //             $queryResultAux->setValor($racaudoAux);
                //             $queryResultAux->setUsuario($this->getUser());
                //         }
                //         $i=$i+1;
                //     }
                // }

                $tramite->addRecaudo($recaudo);
                
                // if($request->get("nombreRecaudo")!="ObservacionVeredicto"){
                    $entityEstado = $em->getRepository('TramiteBundle:Estado');
                    
                    $aprobada = $entityEstado->findOneBy(["nombre" => 'Aprobada']);
                    $enProceso = $entityEstado->findOneBy(["nombre" => 'En Proceso']);
                    $negada = $entityEstado->findOneBy(["nombre" => 'Negada']);
                    $finalizada = $entityEstado->findOneBy(["nombre" => 'Finalizada']);
                    
                    $transicion = new Transicion();
                    
                    if($request->get("nombreRecaudo")=="CausalDesierto"){
                        $estado = $finalizada;
                        $transicion->setDoc_info("Declarado Concurso Desierto.");
                    }else if($request->get("nombreRecaudo")=="FechaEvaluacion"){
                        $estado = $enProceso;
                        $transicion->setDoc_info("Se registro la fecha de Presentacion.");
                        $concurso->setFechaPresentacion(new \DateTime($recaudo->getValor()));
                    }
                    
                    // if($request->get("valorRecaudo")=="Si"){
                    //     if($request->get("nombreRecaudo")=="Veredicto"){
                    //         $estado = $aprobada;
                    //         $transicion->setDoc_info("Aprobaron la solicitud.");
                    //     }else{
                    //         $estado = $enProceso;
                    //         if($request->get("nombreRecaudo")=="AprobarPresupuesto"){
                    //             $transicion->setDoc_info("Aprobaron el presupuesto para la solicitud.");
                    //         }else if($request->get("nombreRecaudo")=="AprobarJurado"){
                    //             $transicion->setDoc_info("Aprobaron el jurado de la solicitud.");
                    //         }
                    //     }
                    // }else if($request->get("valorRecaudo")=="No"){
                    //     if($request->get("nombreRecaudo")=="AprobarPresupuesto" || $request->get("nombreRecaudo")=="Veredicto"){
                    //         $estado = $negada;
                    //         if($request->get("nombreRecaudo")=="AprobarPresupuesto"){
                    //             $transicion->setDoc_info("No aprobaron el presupuesto para la solicitud.");
                    //         }else{
                    //             $transicion->setDoc_info("No aprobaron la solicitud.");
                    //         }
                    //     }else if($request->get("nombreRecaudo")=="AprobarJurado"){
                    //         $estado = $enProceso;
                    //         $transicion->setDoc_info("No aprobaron el jurado de la solicitud.");
                    //     }
                    // }else if($request->get("nombreRecaudo")=="CambioJurado"){
                    //     $estado = $enProceso;
                    //     $transicion->setDoc_info("Se cambió el jurado de la solicitud.");
                    // }else if($request->get("nombreRecaudo")=="FechaRecepDoc"){
                    //     $estado = $finalizada;
                    //     $transicion->setDoc_info("Ha finalizado el proceso de la solicitud.");
                    // }
                    
                    $transicion->setFecha(new \DateTime("now"));
                    $transicion->setEstado($estado);                  
                    $transicion->setEstadoConsejo($estado);
                    
                    $tramite->addTransicion($transicion);
                
                // }
                
                $em->persist($tramite);
                
                $em->flush();
                
                // if($request->get("nombreRecaudo")=="CambioJurado"){
                //     return new JsonResponse(array(
                //         'estado' => 'Insertado',
                //         'mensaje' => 'Nuevo Jurado guardado exitosamente!.'
                //     ));
                // }else{
                    return new JsonResponse(array(
                        'estado' => 'Insertado',
                        'mensaje' => 'Datos Enviados exitosamente!.'
                    ));
                // }
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

}
