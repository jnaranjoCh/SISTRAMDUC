<?php
 
namespace RegistroUnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use RegistroUnicoBundle\Entity\UsuarioFechaCargo;
use ClausulasContractualesBundle\Entity\Hijo;
use RegistroUnicoBundle\Entity\Revista;
use RegistroUnicoBundle\Entity\Participante;
use RegistroUnicoBundle\Entity\Registro;
use RegistroUnicoBundle\Entity\Estatus;
use RegistroUnicoBundle\Entity\Nivel;
use RegistroUnicoBundle\Entity\Cargo;
use TramiteBundle\Entity\Recaudo;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Rol;

class RegistrarDatosController extends Controller
{

    public function registrarDatosUsuarioAction($apr = "initial")
    {
        return $this->render('RegistroUnicoBundle:RegistrarDatos:registrar_datos.html.twig');
    }
    
    public function guardarDatosAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $this->registerSectionOne($request->get('personalData'));
            $this->registerSectionTwo($request->get('cargoData'),$request->get('personalData')[16]);
            return new JsonResponse($this->registerSectionThree($request->get('registrosData'),$request->get('participantesData'),$request->get('revistasData'),$request->get('personalData')[16]));
            $this->registerSectionFour($request->get('hijoData'),$request->get('personalData')[16]);
            return new JsonResponse("Datos guardados");
        }
        else
            return new JsonResponse("Error");
    }
    
    public function guardarArchivosAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_POST['checkboxHijos'])){
            $i = 0;
            $countIdHijo = $this->getDoctrine()
                               ->getManager()
                               ->createQuery('SELECT MAX(r.id) AS lastId FROM TramiteBundle:Recaudo r')
                               ->getResult();
            $countIdCountHijo = count($_FILES['input2']['tmp_name']);
            
            $aux = ($countIdHijo[0]['lastId'] - $countIdCountHijo)+1;
            while($i < $countIdCountHijo){
                $recaudo = $em->getRepository('TramiteBundle:Recaudo')
                             ->findOneById($aux);
                $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/acta_nacimiento/hijos/';
                $fichero_subido = $dir_subida.$recaudo->getName();
                $arr = explode("_",$recaudo->getName());
                $arr = $arr[count($arr)-1];
                $arr = explode(".",$arr);
                $hijo = $em->getRepository('ClausulasContractualesBundle:Hijo')
                             ->findOneById($arr[0]);
                $hijo->setPartidaNacimientoUrl($fichero_subido);
                $recaudo->setPath($fichero_subido);
                $em->flush();
                move_uploaded_file($_FILES['input2']['tmp_name'][$i], $fichero_subido);
                $aux++;
                $i++;
            }
        }
        
        $user = $em->getRepository('AppBundle:Usuario')
                      ->findOneByCorreo($_POST['mail']);
        
        
        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/cedula/users/';
        $fichero_subido = $dir_subida."cedula_".$user->getCedula().".pdf";
        
        $newRecaudo = new Recaudo();
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')
                            ->findOneByNombre('Cedula');
        $newRecaudo->setPath($fichero_subido);
        $newRecaudo->setName("cedula_".$user->getCedula().".pdf");
        $newRecaudo->setFechaVencimiento(new \DateTime($_POST['FechaVencimientoCedulaDatos']));
        $newRecaudo->setUsuario($user);
        $newRecaudo->setTipoRecaudo($tipo_recaudo);
        $newRecaudo->setTabla("Usuario");
        $em->persist($newRecaudo);
        
        if(move_uploaded_file($_FILES['input3']['tmp_name'][0], $fichero_subido)) {
            
            $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/RIF/users/';
            $fichero_subido = $dir_subida."rif_".$user->getCedula().".pdf";
            
            $newRecaudo = new Recaudo();
            $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')
                                ->findOneByNombre('RIF');
            $newRecaudo->setPath($fichero_subido);
            $newRecaudo->setName("rif_".$user->getCedula().".pdf");
            $newRecaudo->setFechaVencimiento(new \DateTime($_POST['FechaVencimientoRifDatos']));
            $newRecaudo->setUsuario($user);
            $newRecaudo->setTipoRecaudo($tipo_recaudo);
            $newRecaudo->setTabla("Usuario");
            $em->persist($newRecaudo);
            
            if(move_uploaded_file($_FILES['input3']['tmp_name'][1], $fichero_subido)) {
                
                $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/acta_nacimiento/users/';
                $fichero_subido = $dir_subida."acta_nacimiento_".$user->getCedula().".pdf";

                $newRecaudo = new Recaudo();
                $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')
                                    ->findOneByNombre('Partida de Nacimiento');
                $newRecaudo->setPath($fichero_subido);
                $newRecaudo->setName("acta_nacimiento_".$user->getCedula().".pdf");
                $newRecaudo->setFechaVencimiento(new \DateTime($_POST['FechaVencimientoActaNacimientoDatos']));
                $newRecaudo->setUsuario($user);
                $newRecaudo->setTipoRecaudo($tipo_recaudo);
                $newRecaudo->setTabla("Usuario");
                $em->persist($newRecaudo);
                $user->setIsRegister(1);
                $em->flush();
                
                if(move_uploaded_file($_FILES['input3']['tmp_name'][2], $fichero_subido)) {
                    return new RedirectResponse($this->generateUrl('registro_datos_index',array('apr' => 'success')));
                }else{
                    return new RedirectResponse($this->generateUrl('registro_datos_index',array('apr' => 'error')));
                }
            }else{
                return new RedirectResponse($this->generateUrl('registro_datos_index',array('apr' => 'error')));
            }
        }else{
            return new RedirectResponse($this->generateUrl('registro_datos_index',array('apr' => 'error')));
        }
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
                return new JsonResponse($encontrado->getActivo() && !$encontrado->getIsRegister());
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
        $data["Registro Completo"]="";
        $data["Copiar"]="";
        foreach($object as $value)
        {
           $data["Email"] = '<label id="email_'.$i.'" >'.$value->getCorreo().'</label>';
           $data["Copiar"] = '<div class="col-md-2">
                                <div class="form-group has-feedback">
                                      <button id="copiar_'.$i.'" type="button" class="btn btn-primary btn-block btn-flat">Seleccionar</button>
                                </div>
                              </div>';
           if($value->getActivo())
               $data["Estatus"]="Activo";
           else
               $data["Estatus"]="Inactivo";
           
           if($value->getIsRegister())
               $data["Registro Completo"]="SI";
           else
               $data["Registro Completo"]="NO";
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
    
    private function registerSectionOne($user)
    {
        $em = $this->getDoctrine()->getManager();
        $newUser = $em->getRepository('AppBundle:Usuario')
                      ->findOneByCorreo($user[16]);
        
        if (!$newUser) {
            throw $this->createNotFoundException(
                'Usuario no encontrado por el correo '.$user[16]
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
        $newUser->setDireccion($user[15]);
        
        $em->flush();
    }
    
    private function registerSectionTwo($cargos,$email)
    {
        $i = 0;
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Usuario')
                      ->findOneByCorreo($email);
    
        if (!$user) {
            throw $this->createNotFoundException(
                'Usuario no encontrado por el correo '.$email
            );
        }
        
        foreach($cargos as $cargo){
          $car = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('RegistroUnicoBundle:Cargo')
                      ->findOneByDescription($cargo['nombre']);
          $UsuarioFechaCargo = new UsuarioFechaCargo();
          $UsuarioFechaCargo->setDate(new \DateTime($cargo['fechaInicio']));
        
          $user->addUsuarioFechaCargos($UsuarioFechaCargo);
          $car->addUsuarioFechaCargos($UsuarioFechaCargo);
        }
        $em->flush();
    }
    
    private function registerSectionThree($registros,$participantes,$revistas,$email)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Usuario')
                      ->findOneByCorreo($email);
    
        if (!$user) {
            throw $this->createNotFoundException(
                'Usuario no encontrado por el correo '.$email
            );
        }
        
        $i = -1;
        $j = -1;
        $valaux = -1;
        $idsparticipantes = [];
        $participantess[] = [];
        if($participantes != null){
            foreach($participantes as $participante){
                $newParticipante = new Participante();
                $newParticipante->setNombre($participante['nombre']);
                $newParticipante->setCedula($participante['cedula']);
                $em = $this->getDoctrine()->getManager();
                $em->persist($newParticipante);
                $em->flush();
                
                $id = $this->getDoctrine()
                           ->getManager()
                           ->createQuery('SELECT MAX(p.id) AS lastId FROM RegistroUnicoBundle:Participante p')
                           ->getResult();
                           
                if($valaux != $participante['idRegistro'])
                {
                    $i++;
                    $valaux = $participante['idRegistro'];
                    $idsparticipantes[$i] = $participante['idRegistro'];
                    $j = -1;            
                }
                $j++;
                $participantess[$i][$j] =  $this->getDoctrine()
                                                ->getManager()
                                                ->getRepository('RegistroUnicoBundle:Participante')
                                                ->findOneById($id[0]['lastId']);
            }
        }
        
        $i = -1;
        $j = -1;
        $valaux = -1;
        $idsrevistas = [];
        $revistass[] = [];
        if($revistas != null){
            foreach($revistas as $revista){
                if($revista != null){
                    $newRevista = new Revista();
                    $newRevista->setDescription($revista['revista']);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($newRevista);
                    $em->flush();
    
                    $id = $this->getDoctrine()
                               ->getManager()
                               ->createQuery('SELECT MAX(r.id) AS lastId FROM RegistroUnicoBundle:Revista r')
                               ->getResult();
                    
                    if($valaux != $revista['idRegistro'])
                    {
                        $i++;
                        $valaux = $revista['idRegistro'];
                        $idsrevistas[$i] = $revista['idRegistro'];
                        $j = -1;
                    }
                    $j++;
                    $revistass[$i][$j] =  $this->getDoctrine()
                                               ->getManager()
                                               ->getRepository('RegistroUnicoBundle:Revista')
                                               ->findOneById($id[0]['lastId']);
                }    
            }
        }
        
        $pos = -1;
        $i = 0;
        $registross = [];
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
            
            if(in_array($registro['idRegistro'],$idsrevistas)){
                $pos = array_search($registro['idRegistro'],$idsrevistas);
                $newRegistro->addRevistas($revistass[$pos]);
            }else if(in_array($registro['idRegistro'],$idsparticipantes)){
                $pos = array_search($registro['idRegistro'],$idsparticipantes);
                $newRegistro->addParticipantes($participantess[$pos]);
            }
            
            $registross[$i] = $newRegistro;
            $em = $this->getDoctrine()->getManager();
            $em->persist($newRegistro);
            $em->flush();
            $i++;
        }
        
        $user->addRegistros($registross);
        $em->flush();
    }
    
     private function registerSectionFour($hijos,$email)
     {
         if($hijos != null){
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:Usuario')
                          ->findOneByCorreo($email);
        
            if (!$user) {
                throw $this->createNotFoundException(
                    'Usuario no encontrado por el correo '.$email
                );
            }
            
            $i = 0;
            $hijoss = [];
            foreach($hijos as $hijo){
                 
                 $newHijo = new Hijo();
                 $newHijo->setCedulaMadre($hijo['ciMadre']);
                 $newHijo->setCedulaPadre($hijo['ciPadre']);
                 if($hijo['ciHijo'] == "")
                    $newHijo->setCedulaHijo(null);
                 else
                    $newHijo->setCedulaHijo($hijo['ciHijo']);
                 $newHijo->setFechaNacimiento(new \DateTime($hijo['fechaNacimiento']));
                 $newHijo->setPrimerNombre($hijo['primerNombre']);
                 $newHijo->setSegundoNombre($hijo['segundoNombre']);
                 $newHijo->setPrimerApellido($hijo['primerApellido']);
                 $newHijo->setSegundoApellido($hijo['segundoApellido']);
                 $newHijo->setNacionalidad($hijo['nacionalidad']);
                 $newHijo->setPartidaNacimientoUrl('');
                 $hijoss[$i] = $newHijo;
                 $em->persist($newHijo);
                 
                 $newRecaudo  = new Recaudo();
                 $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')
                                    ->findOneByNombre('Partida de Nacimiento');
                 $newRecaudo->setName("acta_nacimiento_".$newHijo->getId().".pdf");
                 $newRecaudo->setFechaVencimiento(new \DateTime($hijo['fechaVencimiento']));
                 $newRecaudo->setUsuario($user);
                 $newRecaudo->setTipoRecaudo($tipo_recaudo);
                 $newRecaudo->setTabla("Hijo");
                 $newRecaudo->setPath("");
                 $em = $this->getDoctrine()->getManager();
                 $em->persist($newRecaudo);
                 $em->flush();
                 $i++;
            }
            $user->addHijos($hijoss);
            $em->flush();
        } 
    }
}