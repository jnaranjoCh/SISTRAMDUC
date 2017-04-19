<?php
 
namespace RegistroUnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use RegistroUnicoBundle\Entity\Participante;
use RegistroUnicoBundle\Entity\Registro;
use RegistroUnicoBundle\Entity\Estatus;
use RegistroUnicoBundle\Entity\Nivel;
use RegistroUnicoBundle\Entity\Cargo;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Rol;

class RegistrarDatosController extends Controller
{
    public function registrarDatosUsuarioAction()
    {
        return $this->render('RegistroUnicoBundle:RegistrarDatos:registrar_datos.html.twig');
    }
    
    public function guardarDatosAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $this->registerUser($request->get('personalData'));
            $this->registerCargos($request->get('cargoData'),$request->get('personalData')[15]);
            $this->registerRegistros($request->get('registrosData'),$request->get('participantesData'),$request->get('revistasData'),$request->get('personalData')[15]);
            ////
            /*$request->get('hijoData') */
            return new JsonResponse('if');
        }
        else
            return new JsonResponse("else");
    }
    
    public function enviarEmailsAjaxAction(Request $request)
    {
        return new JsonResponse($this->getEmails($this->getAll("AppBundle:","Usuario")));
    }   
    
    public function buscarEmailAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $encontrado = $this->getOneEmail("AppBundle:","Usuario",$request->get("Email"));

            if (!$encontrado) {
                return new JsonResponse(0);
            }else
                return new JsonResponse($encontrado->getActivo());
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }
    
    public function enviarDataAjaxAction(Request $request)
    {
        $val[][] = "";
        if($request->isXmlHttpRequest())
        {
            $estatus = $this->getAll("RegistroUnicoBundle:","Estatus");
            $nivel = $this->getAll("RegistroUnicoBundle:","Nivel");
            $tipo_regitro = $this->getAll("RegistroUnicoBundle:","TipoRegistro");
            $cargo = $this->getAll("RegistroUnicoBundle:","Cargo");
            
            $rol = $this->getAll("AppBundle:","Rol");

            if (!$estatus || !$nivel || !$tipo_regitro || !$cargo || !$rol) {
                 throw $this->createNotFoundException('Error al obtener datos iniciales');
            }else
            {
                $val = $this->bdToArrayDescription($estatus,'estatus',$val);
                $val = $this->bdToArrayDescription($nivel,'nivel',$val);
                $val = $this->bdToArrayDescription($tipo_regitro,'tipo_registro',$val);
                $val = $this->bdToArrayDescription($cargo,'cargo',$val);
                $val = $this->bdToArrayNombre($rol,'rol',$val);
                return new JsonResponse($val);
            }
        }
        else
             throw $this->createNotFoundException('Error al solicitar datos');
    }

    public function obtenerIdAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $value = $this->getDoctrine()
                          ->getManager()
                          ->createQuery('SELECT MAX(r.id) AS lastId FROM RegistroUnicoBundle:Registro r')
                          ->getResult();
            return new JsonResponse($value);
        }
        else
             throw $this->createNotFoundException('Error al obtener los datos');
    }
    
    private function getEmails($object)
    {
        $i = 0;
        $datas=null;
        $data["Email"]="";
        $data["Estatus"]="";
        foreach($object as $value)
        {
           $data["Email"] = $value->getCorreo();
           if($value->getActivo())
               $data["Estatus"]="Activo";
           else
               $data["Estatus"]="Inactivo";
           $datas[$i] = $data;
           $i++;
        }
        
        return array(
            "draw"            => 1,
	        "recordsTotal"    => $i,
	        "recordsFiltered" => $i,
	        "data"            => $datas
        );
    }
    
    private function bdToArrayDescription($object,$entidad,$val)
    {
        $i = 0;
        foreach($object as $value)
        {
           $val[$entidad][$i] = $value->getDescription();
           $i++;
        }
        return $val;
    }
    
    private function bdToArrayNombre($object,$entidad,$val)
    {
        $i = 0;
        foreach($object as $value)
        {
           $val[$entidad][$i] = $value->getNombre();
           $i++;
        }
        return $val;
    }
    
    private function getOneEmail($bundle,$entidad,$email)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByCorreo($email);
    }
    
    private function getAll($bundle,$entidad)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findAll();
    }
    
    private function getRolName($bundle,$entidad,$id)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneById($id);
    }
    
    private function registerUser($user)
    {
        $em = $this->getDoctrine()->getManager();
        $newUser = $em->getRepository('AppBundle:Usuario')
                      ->findOneByCorreo($user[15]);
        
        if (!$newUser) {
            throw $this->createNotFoundException(
                'Usuario no encontrado por el correo '.$user[15]
            );
        }
        
        $newUser->setPrimerNombre($user[0]);
        $newUser->setSegundoNombre($user[1]);
        $newUser->setPrimerApellido($user[2]);
        $newUser->setSegundoApellido($user[3]);
        $newUser->setNacionalidad($user[4]);
        $newUser->setFechaNacimiento(new \DateTime($user[5]));
        $newUser->setEdad($user[6]);
        $newUser->setSexo($user[7]);
        $newUser->setRif('J-'.$user[8]);
        $newUser->setTelefono($user[9].'-'.$user[10]);
        $newUser->setDireccion($user[14]);
        
        $em->flush();
    }
    
    private function registerCargos($cargos,$email)
    {
        $i = 0;
        $em = $this->getDoctrine()->getManager();
        $newUser = $em->getRepository('AppBundle:Usuario')
                      ->findOneByCorreo($email);
    
        if (!$newUser) {
            throw $this->createNotFoundException(
                'Usuario no encontrado por el correo '.$email
            );
        }
        
        foreach($cargos as $cargo){
          $cargoss[$i] = $this->getDoctrine()
                              ->getManager()
                              ->getRepository('RegistroUnicoBundle:Cargo')
                              ->findOneByDescription($cargo);
          $i++;
        }
        $newUser->addCargos($cargoss);
        $em->flush();
    }
    
    private function registerRegistros($registros,$participantes,$revistas,$email)
    {
        $i = 0;
        $addParticipantes = false;
        $addRevistas = false;
        foreach($registros as $registro){
            
            $newRegistro =  new Registro();
            $newRegistro->setTipo($this->getDoctrine()
                                       ->getManager()
                                       ->getRepository('RegistroUnicoBundle:TipoRegistro')
                                       ->findOneByDescription($registro['tipoDeReferencia']));
            $newRegistro->setNivel($this->getDoctrine()
                                        ->getManager()
                                        ->getRepository('RegistroUnicoBundle:Nivel')
                                        ->findOneByDescription($registro['nivel']));
            $newRegistro->setEstatus($this->getDoctrine()
                                          ->getManager()
                                          ->getRepository('RegistroUnicoBundle:Estatus')
                                          ->findOneByDescription($registro['estatus']));
            $newRegistro->setInstitucionEmpresa($registro['empresaInstitucion']);
            $newRegistro->setDescription($registro['descripcion']);
            $newRegistro->setAño($registro['anio']);
            
            if($participantes != null){
                $j = 0;
                while($i < count($participantes) && $participantes[$i]['idRegistro'] == $registro['idRegistro']){
                    $addParticipantes = true;
                    $newParticipante = new Participante();
                    $newParticipante->setNombre($participantes[$i]['nombre']);
                    $newParticipante->setCedula($participantes[$i]['cedula']);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($newParticipante);
                    $em->flush();
                    
                    $id = $this->getDoctrine()
                               ->getManager()
                               ->createQuery('SELECT MAX(p.id) AS lastId FROM RegistroUnicoBundle:Participante p')
                               ->getResult();
                    $participantess[$j] =  $this->getDoctrine()
                                                ->getManager()
                                                ->getRepository('RegistroUnicoBundle:Participante')
                                                ->findOneById($id[0]['lastId']);
                    $j++;
                    $i++;
                    
                }
                if($addParticipantes){
                    $addParticipantes = false;
                    $newRegistro->addParticipantes($participantess);
                }
            }
            
            if($revistas != null){
                $j = 0;
                while($i < count($revistas) && $revistas[$i]['idRegistro'] == $registro['idRegistro']){
                    $addRevistas = true;
                    $newRevista = new Revista();
                    $newRevista->setDescription($revistas[$i]['revista']);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($newRevista);
                    $em->flush();
    
                    $id = $this->getDoctrine()
                               ->getManager()
                               ->createQuery('SELECT MAX(r.id) AS lastId FROM RegistroUnicoBundle:Revista r')
                               ->getResult();
                    
                    $revistass[$j] =  $this->getDoctrine()
                                           ->getManager()
                                           ->getRepository('RegistroUnicoBundle:Revista')
                                           ->findOneById($id[0]['lastId']);
                    $j++;
                    $i++;
                }
                if($addRevistas){
                    $addRevistas = false;
                    $newRegistro->addRevistas($revistass);
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($newRegistro);
            $em->flush();
        }
    }
}