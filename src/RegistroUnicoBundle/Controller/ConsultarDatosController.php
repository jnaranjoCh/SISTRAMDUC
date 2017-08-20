<?php

namespace RegistroUnicoBundle\Controller;

use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ClausulasContractualesBundle\Entity\Hijo;
use RegistroUnicoBundle\Entity\UsuarioFechaCargo;
use RegistroUnicoBundle\Entity\Estatus;
use RegistroUnicoBundle\Entity\Revista;
use RegistroUnicoBundle\Entity\Participante;
use RegistroUnicoBundle\Entity\Registro;
use RegistroUnicoBundle\Entity\TipoRegistro;
use RegistroUnicoBundle\Entity\Nivel;
use TramiteBundle\Entity\TipoRecaudo;
use TramiteBundle\Entity\Recaudo;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Rol;
use \stdClass;

class ConsultarDatosController extends Controller
{
    
    public function consultarRegistroAction($apr = "initial")
    {
        return $this->render('RegistroUnicoBundle:ConsultarDatos:consultar_registro.html.twig');
    }
    
    public function enviarEmailsAjaxAction(Request $request)
    {
        return new JsonResponse($this->getEmails($this->getAll("AppBundle:","Usuario")));
    }   
    
    public function guardarArchivosAction(Request $request, $email, $execute)
    {
        $p1 = $p2 = [];
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Usuario')
                   ->findOneByCorreo($email);
     
        if (!empty($_FILES['input3']['name']) && $execute) {
          $recauds = $em->createQuery('SELECT r
                                         FROM TramiteBundle:Recaudo r
                                            INNER JOIN r.usuario u
                                         WHERE u.id = :idUsuario 
                                         AND r.tabla = :tabla')
                            ->setParameter('idUsuario',$user->getId())
                            ->setParameter('tabla','Usuario')
                            ->getResult();
           for($i = 0; $i < 3; $i++)
           {
               if(strpos($_FILES['input3']['name'][$i],'Cedula.pdf') !== false)
               {
                   if(strpos($recauds[0]->getPath(),'cedula') !== false)
                   {
                        move_uploaded_file($_FILES['input3']['tmp_name'][$i], $recauds[0]->getPath());
                        $p1[0] = $recauds[0]->getPath();
                   }else if(strpos($recauds[1]->getPath(),'cedula') !== false)
                   {
                       move_uploaded_file($_FILES['input3']['tmp_name'][$i], $recauds[1]->getPath());
                       $p1[0] = $recauds[1]->getPath();
                   }else if(strpos($recauds[2]->getPath(),'cedula') !== false)
                   {
                       move_uploaded_file($_FILES['input3']['tmp_name'][$i], $recauds[2]->getPath());
                       $p1[0] = $recauds[2]->getPath();
                   }
                 
               }
               else if(strpos($_FILES['input3']['name'][$i],'Rif.pdf') !== false)
               {
                   if(strpos($recauds[0]->getPath(),'rif') !== false)
                   {
                        move_uploaded_file($_FILES['input3']['tmp_name'][$i], $recauds[0]->getPath());
                        $p1[1] = $recauds[0]->getPath();
                   }else if(strpos($recauds[1]->getPath(),'rif') !== false)
                   {
                       move_uploaded_file($_FILES['input3']['tmp_name'][$i], $recauds[1]->getPath());
                       $p1[1] = $recauds[1]->getPath();
                   }else if(strpos($recauds[2]->getPath(),'rif') !== false)
                   {
                       move_uploaded_file($_FILES['input3']['tmp_name'][$i], $recauds[2]->getPath());
                       $p1[1] = $recauds[2]->getPath();
                   }
               }
               else if(strpos($_FILES['input3']['name'][$i],'Acta_nacimiento.pdf') !== false)
               {
                   if(strpos($recauds[0]->getPath(),'acta_nacimiento') !== false)
                   {
                        move_uploaded_file($_FILES['input3']['tmp_name'][$i], $recauds[0]->getPath());
                        $p1[2] = $recauds[0]->getPath();
                   }else if(strpos($recauds[1]->getPath(),'acta_nacimiento') !== false)
                   {
                       move_uploaded_file($_FILES['input3']['tmp_name'][$i], $recauds[1]->getPath());
                       $p1[2] = $recauds[1]->getPath();
                   }else if(strpos($recauds[2]->getPath(),'acta_nacimiento') !== false)
                   {
                       move_uploaded_file($_FILES['input3']['tmp_name'][$i], $recauds[2]->getPath());
                       $p1[2] = $recauds[2]->getPath();
                   }
               }
           }
           $p2[0] = ['caption' => "Cedula<br/>".$user->getPrimerNombre()." ".$user->getPrimerApellido(), 'width' => "120px", 'key' => 1, 'showDelete' => false];
           $p2[1] = ['caption' => "Acta nacimiento<br/>".$user->getPrimerNombre()." ".$user->getPrimerApellido(), 'width' => "120px", 'key' => 2, 'showDelete' => false];
           $p2[2] = ['caption' => "Rif<br/>".$user->getPrimerNombre()." ".$user->getPrimerApellido(), 'width' => "120px", 'key' => 3, 'showDelete' => false];
        }
        
        if (!empty($_FILES['input2']['name']) && $execute) {
            $i = 0;
            $aux = $aux2 = [];
            $recauds = $em->createQuery('SELECT r
                                 FROM TramiteBundle:Recaudo r
                                    INNER JOIN r.usuario u
                                 WHERE u.id = :idUsuario 
                                 AND r.tabla = :tabla')
                    ->setParameter('idUsuario',$user->getId())
                    ->setParameter('tabla','Hijo')
                    ->getResult();
            $k = count($_FILES['input2']['tmp_name'])-1;
            for($j = 0; $j <= $k; $j++)
            {    
                $aux[$j] = 0;
            }
            
            for($j = 0; $j <= $k; $j++)
            {
                $aux[$j] = (int)explode("_",$_FILES['input2']['name'][$j])[2];
                $aux2[$j] = $_FILES['input2']['tmp_name'][$j];
            }
            $aux2 = $this->burbuja($aux,$aux2);
            foreach($recauds as $recaud)
            {
                move_uploaded_file($aux2[$i], $recaud->getPath());
                $p1[$i] = $recaud->getPath();
                $j = explode("_",explode(".",$recaud->getName())[0])[count(explode("_",explode(".",$recaud->getName())[0]))-1];
                $p2[$i] = ['caption' => "Acta de nacimiento<br/>".explode("/",$recaud->getPath())[count(explode("/",$recaud->getPath()))-1], 'width' => '120px', 'key' => $j, 'showDelete' => false];
                $i++;
            }
        }
        
        
        return new JsonResponse(array(
            'initialPreview' => $p1, 
            'initialPreviewConfig' => $p2,   
            'append' => false // whether to append these configurations to initialPreview.
                             // if set to false it will overwrite initial preview
                             // if set to true it will append to initial preview
                             // if this propery not set or passed, it will default to true.
        ));
    }
    
    
    private function guardarUrlArchivosUser($email,$fechas)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Usuario')
                   ->findOneByCorreo($email);
        
        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/cedula/users/';
        $fichero_subido = $dir_subida."cedula_".$user->getCedula().".pdf";
        
        $newRecaudo = new Recaudo();
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')
                            ->findOneByNombre('Cedula');
        $newRecaudo->setPath($fichero_subido);
        $newRecaudo->setName("cedula_".$user->getCedula().".pdf");
        list($day, $month, $year) = explode('/', explode(' ', $fechas[0])[0]);
        $newRecaudo->setFechaVencimiento(new \DateTime($year."-".$month."-".$day));
        $newRecaudo->setUsuario($user);
        $newRecaudo->setTipoRecaudo($tipo_recaudo);
        $newRecaudo->setTabla("Usuario");
        $em->persist($newRecaudo);
            
        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/RIF/users/';
        $fichero_subido = $dir_subida."rif_".$user->getCedula().".pdf";
        
        $newRecaudo = new Recaudo();
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')
                            ->findOneByNombre('RIF');
        $newRecaudo->setPath($fichero_subido);
        $newRecaudo->setName("rif_".$user->getCedula().".pdf");
        list($day, $month, $year) = explode('/', explode(' ', $fechas[1])[0]);
        $newRecaudo->setFechaVencimiento(new \DateTime($year."-".$month."-".$day));
        $newRecaudo->setUsuario($user);
        $newRecaudo->setTipoRecaudo($tipo_recaudo);
        $newRecaudo->setTabla("Usuario");
        $em->persist($newRecaudo);
            
        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/acta_nacimiento/users/';
        $fichero_subido = $dir_subida."acta_nacimiento_".$user->getCedula().".pdf";

        $newRecaudo = new Recaudo();
        $tipo_recaudo = $em->getRepository('TramiteBundle:TipoRecaudo')
                            ->findOneByNombre('Partida de Nacimiento');
        $newRecaudo->setPath($fichero_subido);
        $newRecaudo->setName("acta_nacimiento_".$user->getCedula().".pdf");
        list($day, $month, $year) = explode('/', explode(' ', $fechas[2])[0]);
        $newRecaudo->setFechaVencimiento(new \DateTime($year."-".$month."-".$day));
        $newRecaudo->setUsuario($user);
        $newRecaudo->setTipoRecaudo($tipo_recaudo);
        $newRecaudo->setTabla("Usuario");
        $em->persist($newRecaudo);
        $user->setIsRegister(1);
        $em->flush();
        
    }
    private function guardarUrlArchivosHijos($email)
    {
        $fileHijos = [];
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Usuario')
                   ->findOneByCorreo($email);
        $recauds = $em->createQuery('SELECT r
                                 FROM TramiteBundle:Recaudo r
                                    INNER JOIN r.usuario u
                                 WHERE u.id = :idUsuario')
                    ->setParameter('idUsuario',$user->getId())
                    ->getResult();
        $hijs = $user->getHijosAsObjects();
        $j = count($hijs)-1;
        $i = 0;
        foreach($recauds as $recaud)
        {
            $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/acta_nacimiento/hijos/';
            $fichero_subido = $dir_subida.$recaud->getName();
            $recaud->setPath($fichero_subido);
            $hijs[$j]->setPartidaNacimientoUrl($fichero_subido);
            $i++;
            $j--;
        }
        $em->flush();
    }
    
    public function enviarRegistrosDeUsuarioAjaxAction(Request $request)
    {
        $data = $this->getDoctrine()
                      ->getManager()
                      ->createQuery('SELECT u,r FROM AppBundle:Usuario u JOIN u.registros r WHERE u.correo = :email')
                      ->setParameter('email',$request->get('email'))
                      ->getResult()[0]->getRegistros($request->get('assets'));
                      
        $htmlTipoRegistro = "";
        $htmlNivel = "";
        $htmlEstatus = "";
        
        $tipo_registros = $this->getDoctrine()
                               ->getManager()
                               ->getRepository('RegistroUnicoBundle:TipoRegistro')
                               ->findAll();
                               
        $niveles = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('RegistroUnicoBundle:Nivel')
                        ->findAll();
        
        $estatus = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('RegistroUnicoBundle:Estatus')
                        ->findAll();
                       
        for($i = 0; $i < $data->num; $i++)
        {
            $htmlEstatus = '<select id="Estatus'.$i.'" class="form-control select2" style="width: 240px;">';
            $htmlEstatus = $htmlEstatus.'<option value="">Seleccione una opción</option>';
            foreach($estatus as $estatu){
                if($estatu->getDescription() == $data->data[$i]['Estatus']){
                     $htmlEstatus =  $htmlEstatus."<option value='".$data->data[$i]['Estatus']."' selected='selected'>".$data->data[$i]['Estatus']."</option>";
                }else{
                     $htmlEstatus =  $htmlEstatus."<option value='".$estatu->getDescription()."'>".$estatu->getDescription()."</option>";
                }
                
            }
            $htmlEstatus =  $htmlEstatus."</select>";
            $data->data[$i]['Estatus'] = $htmlEstatus;
            
            $htmlNivel = '<select id="Nivel'.$i.'" class="form-control select2" style="width: 240px;">';
            $htmlNivel = $htmlNivel.'<option value="">Seleccione una opción</option>';
            foreach($niveles as $nivel){
                if($nivel->getDescription() == $data->data[$i]['Nivel']){
                     $htmlNivel =  $htmlNivel."<option value='".$data->data[$i]['Nivel']."' selected='selected'>".$data->data[$i]['Nivel']."</option>";
                }else{
                     $htmlNivel =  $htmlNivel."<option value='".$nivel->getDescription()."'>".$nivel->getDescription()."</option>";
                }
                
            }
            $htmlNivel =  $htmlNivel."</select>";
            $data->data[$i]['Nivel'] = $htmlNivel;
            
            $htmlTipoRegistro = '<select id="Tipo'.$i.'" class="form-control select2" style="width: 240px;">';
            $htmlTipoRegistro = $htmlTipoRegistro.'<option value="">Seleccione una opción</option>';
            foreach($tipo_registros as $tipo_registro){
                if($tipo_registro->getDescription() == $data->data[$i]['TipoDeReferencia']){
                    $htmlTipoRegistro = $htmlTipoRegistro."<option value='".$data->data[$i]['TipoDeReferencia']."' selected='selected'>".$data->data[$i]['TipoDeReferencia']."</option>";
                }else{
                    $htmlTipoRegistro = $htmlTipoRegistro."<option value='".$tipo_registro->getDescription()."'>".$tipo_registro->getDescription()."</option>";
                }
                
            }
            $htmlTipoRegistro = $htmlTipoRegistro."</select>";
            $data->data[$i]['TipoDeReferencia'] = $htmlTipoRegistro;
            
        }
        
        return new JsonResponse( array(
            "draw"            => 1,
	        "recordsTotal"    => $data->num,
	        "recordsFiltered" => $data->num,
	        "data"            => $data->data 
        ));
    }
    
    public function enviarRegistrosDeParticipantesAjaxAction(Request $request)
    {
        $data = $this->getDoctrine()
                      ->getManager()
                      ->createQuery('SELECT u,r,p
                                     FROM AppBundle:Usuario u
                                        INNER JOIN u.registros r
                                        INNER JOIN r.participantes p
                                     WHERE u.correo = :email')
                      ->setParameter('email',$request->get('email'))
                      ->getResult();
        
        if($data != null)        
            $data = $data[0]->getRegistrosParticipantes($request->get('assets'));
        else 
        {
            $data = new stdClass;
            $data->data = null;
            $data->num = 0;
        }
        
        return new JsonResponse( array(
            "draw"            => 1,
	        "recordsTotal"    => $data->num,
	        "recordsFiltered" => $data->num,
	        "data"            => $data->data 
        ));
    }
    
    public function enviarRegistrosDeRevistasAjaxAction(Request $request)
    { 
        $data = $this->getDoctrine()
                      ->getManager()
                      ->createQuery('SELECT u,r,rr
                                     FROM AppBundle:Usuario u
                                        INNER JOIN u.registros r
                                        INNER JOIN r.revistas rr
                                     WHERE u.correo = :email')
                      ->setParameter('email',$request->get('email'))
                      ->getResult();
        
        if($data != null)              
            $data = $data[0]->getRegistrosRevistas($request->get('assets'));
        else 
        {
            $data = new stdClass;
            $data->data = null;
            $data->num = 0;
        }
        
        return new JsonResponse(array(
            "draw"            => 1,
	        "recordsTotal"    => $data->num,
	        "recordsFiltered" => $data->num,
	        "data"            => $data->data 
        ));
    }
    
    public function enviarDatosPersonalesAjaxAction(Request $request)
    { 
        $entity = $this->getOneEmail("AppBundle:","Usuario",$request->get("email"));
        $files = $this->getDoctrine()
                      ->getManager()
                      ->createQuery('SELECT r.path, r.fecha_vencimiento
                                     FROM AppBundle:Usuario u
                                        INNER JOIN  u.recaudos r
                                     WHERE u.id = :id AND r.tabla = :tabla')
                      ->setParameter('id',$entity->getId())
                      ->setParameter('tabla','Usuario')
                      ->getResult();
        $dataUser = new stdClass;
        $dataUser->PrimerNombre = $entity->getPrimerNombre();
        $dataUser->SegundoNombre = $entity->getSegundoNombre();
        $dataUser->PrimerApellido = $entity->getPrimerApellido();
        $dataUser->SegundoApellido = $entity->getSegundoApellido();
        $dataUser->Nacionalidad = $entity->getNacionalidad();
        $dataUser->Telefono = $entity->getTelefono();
        $dataUser->Rif = $entity->getRif();
        $dataUser->FechaNacimiento = $entity->getFechaNacimiento();
        $dataUser->Direccion = $entity->getDireccion();
        $dataUser->Sexo = $entity->getSexo();
        $dataUser->Edad = $entity->getEdad();
        $dataUser->Cedula = $entity->getCedula();
        $dataUser->Files = $files;
        
        return new JsonResponse($dataUser);
    }
    
    public function enviarDatosPersonalesHijosPathAjaxAction(Request $request)
    {
        $data = new stdClass;
        $entity = $this->getOneEmail("AppBundle:","Usuario",$request->get("email"));
        $files = $this->getDoctrine()
                      ->getManager()
                      ->createQuery('SELECT r.path
                                     FROM AppBundle:Usuario u
                                        INNER JOIN  u.recaudos r
                                     WHERE u.id = :id AND r.tabla = :tabla')
                      ->setParameter('id',$entity->getId())
                      ->setParameter('tabla','Hijo')
                      ->getResult();
        if(!$files)
        {
            $files = null;
        }
        return new JsonResponse($files);
    }
    
    public function enviarDatosPersonalesHijosAjaxAction(Request $request)
    { 
        $entity = $this->getOneEmail("AppBundle:","Usuario",$request->get("email"));
        $files = $this->getDoctrine()
                      ->getManager()
                      ->createQuery('SELECT r.path, r.fecha_vencimiento
                                     FROM AppBundle:Usuario u
                                        INNER JOIN  u.recaudos r
                                     WHERE u.id = :id AND r.tabla = :tabla')
                      ->setParameter('id',$entity->getId())
                      ->setParameter('tabla','Hijo')
                      ->getResult();
                      
        $hijos = $this->getDoctrine()
                      ->getManager()
                      ->createQuery('SELECT h.cedulaHijo, h.cedulaMadre, h.cedulaPadre,
                                            h.primerNombre, h.segundoNombre, h.primerApellido,
                                            h.segundoApellido, h.nacionalidad, h.fechaNacimiento,
                                            h.partidaNacimientoUrl
                                     FROM AppBundle:Usuario u
                                        INNER JOIN  u.hijos h
                                     WHERE u.id = :id')
                      ->setParameter('id',$entity->getId())
                      ->getResult();
        $i = 0;
        $data[] = [];
        if($hijos)
        {
            
            foreach($hijos as $hijo){
                if($request->get('assets') == "assets")
                    $data[$i]['Delete'] = "<img src='/assets/images/delete.png' width='30px' heigth='30px'/>";
                else
                    $data[$i]['Delete'] = "<img src='/web/assets/images/delete.png' width='30px' heigth='30px'/>";
                $data[$i]['CIMadre'] = '<input id="CIMadre'.$i.'" value="'.$hijo['cedulaMadre'].'" type="number" class="form-control" placeholder="Cedula Madre">';
                $data[$i]['CIPadre'] = '<input id="CIPadre'.$i.'" value="'.$hijo['cedulaPadre'].'" type="number" class="form-control" placeholder="Cedula Padre">';
                $data[$i]['CIHijo'] = '<input id="CIHijo'.$i.'" value="'.$hijo['cedulaHijo'].'" type="number" class="form-control" placeholder="Cedula Hijo">';
                $data[$i]['1erNombre'] = '<input id="1erNombre'.$i.'" value="'.$hijo['primerNombre'].'" type="text" class="form-control" placeholder="Primer Nombre">';
                $data[$i]['2doNombre'] = '<input id="2doNombre'.$i.'" value="'.$hijo['segundoNombre'].'" type="text" class="form-control" placeholder="Segundo Nombre">';
                $data[$i]['1erApellido'] = '<input id="1erApellido'.$i.'" value="'.$hijo['primerApellido'].'" type="text" class="form-control" placeholder="Primer Apellido">';
                $data[$i]['2doApellido'] = '<input id="2doApellido'.$i.'" value="'.$hijo['segundoApellido'].'" type="text" class="form-control" placeholder="Segundo Apellido">';
                $data[$i]['FNacimiento'] = '<div class="row">
                                              <div class="col-xs-12">
                                                <div class="form-group has-feedback">
                                                    <div class="input-group date">
                                                        <input id="datepickerHijo1'.$i.'" value="'.$hijo['fechaNacimiento']->format('d/m/Y H:i').'" name="FNacimiento'.$i.'" type="text" class="form-control" style="width: 240px;"/>
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                              </div>
                                           </div>';
                foreach($files as $file)
                {
                    if($file['path'] == $hijo['partidaNacimientoUrl'])
                        $data[$i]['FVencimientoActa'] = '<div class="row">
                                                          <div class="col-xs-12">
                                                            <div class="form-group has-feedback">
                                                                <div class="input-group date">
                                                                    <input id="datepickerHijo2'.$i.'" value="'.$file['fecha_vencimiento']->format('d/m/Y H:i').'" name="FVencimientoActa'.$i.'" type="text" class="form-control" style="width: 200px;"/>
                                                                    <span class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                          </div>
                                                       </div>';
                }
                 
                if(strcmp($hijo['nacionalidad'],"Venezolano")==0)
                    $data[$i]['Nacionalidad'] = '<select id="NacionalidadDatos'.$i.'" class="form-control select2" style="width: 200xp;" required>
                                                    <option value="">Seleccione una opción</option>
                                                    <option selected="selected" value="Venezolano">Venezolano</option>
                                                    <option value="Extranjero">Extranjero</option>
                                                  </select>';
                else
                    $data[$i]['Nacionalidad'] = '<select id="NacionalidadDatos'.$i.'" class="form-control select2" style="width: 200xp;" required>
                                                    <option value="">Seleccione una opción</option>
                                                    <option value="Venezolano">Venezolano</option>
                                                    <option selected="selected" value="Extranjero">Extranjero</option>
                                                  </select>';
                
                $i++;
            }
        }else
            $data = null;
            
        return new JsonResponse(array(
            "draw"            => 1,
	        "recordsTotal"    => $i,
	        "recordsFiltered" => $i,
	        "data"            => $data 
        ));
    }
    
    public function enviarCargosDeUsuarioAjaxAction(Request $request)
    { 
        $i = 0;
        $data[] = [];
        $cargos = $this->getDoctrine()
                               ->getManager()
                               ->getRepository('RegistroUnicoBundle:Cargo')
                               ->findAll();
        
        $cargosDates = $this->getDoctrine()
                            ->getManager()
                            ->createQuery('SELECT c.description, ufc.date
                                             FROM AppBundle:Usuario u
                                                INNER JOIN u.UsuarioFechaCargos ufc
                                                INNER JOIN ufc.cargos c
                                             WHERE u.correo = :email')
                            ->setParameter('email',$request->get('email'))
                            ->getResult();
        if($cargos && $cargosDates)
        {
            foreach($cargosDates as $cargoDate)
            {
                $htmlCargos = '<select id="Cargos'.$i.'" class="form-control select2" style="width: 240px;">';
                $htmlCargos = $htmlCargos.'<option value="">Seleccione una opción</option>';
                foreach($cargos as $cargo)
                {
                    if($cargoDate['description'] == $cargo->getDescription()){
                         $htmlCargos =  $htmlCargos."<option value='".$cargoDate['description']."' selected='selected'>".$cargoDate['description']."</option>";
                    }else{
                         $htmlCargos =  $htmlCargos."<option value='".$cargo->getDescription()."'>".$cargo->getDescription()."</option>";
                    }
                }
                $htmlCargos =  $htmlCargos."</select>";
                if($request->get('assets') == "assets")
                    $data[$i]['Delete'] = "<img src='/assets/images/delete.png' width='30px' heigth='30px'/>";
                else
                    $data[$i]['Delete'] = "<img src='/web/assets/images/delete.png' width='30px' heigth='30px'/>";
                $data[$i]['Cargo'] = $htmlCargos; 
                $data[$i]['FechaDeInicioEnElCargo'] = '<div class="row">
                                                          <div class="col-xs-12">
                                                            <div class="form-group has-feedback">
                                                                <div class="input-group date">
                                                                    <input id="datepicker'.$i.'" value="'.$cargoDate['date']->format('d/m/Y H:i').'" name="FechaInicioCargoDatos'.$i.'" type="text" class="form-control"/>
                                                                    <span class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                          </div>
                                                       </div>';
                $i++;
            }
        }else
            $data = null;
        return new JsonResponse(array(
            "draw"            => 1,
	        "recordsTotal"    => $i,
	        "recordsFiltered" => $i,
	        "data"            => $data 
        ));
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
    
    private function getEmails($object)
    {
        $i = 0;
        $datas=null;
        $data["Email"]="";
        $data["Estatus"]="";
        $data["Copiar"]="";
        foreach($object as $value)
        {
            if($value->getIsRegister())
            {
              $data["Copiar"] = '<div class="col-md-2">
                    <div class="form-group has-feedback">
                          <button id="copiar_'.$i.'" type="button" class="btn btn-primary btn-block btn-flat">Seleccionar</button>
                    </div>
                  </div>';
               $data["Email"] = '<label id="email_'.$i.'" >'.$value->getCorreo().'</label>';
               if($value->getActivo())
                   $data["Estatus"]="Activo";
               else
                   $data["Estatus"]="Inactivo";
               $datas[$i] = $data;
               $i++;
            }
        }
        
        return array(
            "draw"            => 1,
	        "recordsTotal"    => $i,
	        "recordsFiltered" => $i,
	        "data"            => $datas
        );
    }

    private function getAll($bundle,$entidad)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findAll();
    }
    
    private function getOneEmail($bundle,$entidad,$email)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository($bundle.$entidad)
                    ->findOneByCorreo($email);
    }
    
    public function editarDatosDeUsuarioAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $this->updateSectionOne($request->get('personalData'));
            $this->updateSectionTwo($request->get('cargoData'),$request->get('personalData')[16]);
            $this->updateSectionThree($request->get('registrosData'),$request->get('participantesData'),$request->get('revistasData'),$request->get('personalData')[16]);
            $this->updateSectionFour($request->get('hijoData'),$request->get('personalData')[16],(string)$request->get('input2bool'),(string)$request->get('input3bool'));
            $this->guardarUrlArchivosUser($request->get('personalData')[16],$request->get('fechasArchivos'));
            return new JsonResponse("Datos guardados");
        }
        else
            return new JsonResponse("Error");
    }
    
    private function updateSectionOne($user)
    {
        $em = $this->getDoctrine()->getManager();
        $updateUser = $em->getRepository('AppBundle:Usuario')
                      ->findOneByCorreo($user[16]);
        
        if (!$updateUser) {
            throw $this->createNotFoundException(
                'Usuario no encontrado por el correo '.$user[16]
            );
        }
        
        list($day, $month, $year) = explode('/', explode(' ', $user[5])[0]);
        $updateUser->setPrimerNombre($user[0]);
        $updateUser->setSegundoNombre($user[1]);
        $updateUser->setPrimerApellido($user[2]);
        $updateUser->setSegundoApellido($user[3]);
        $updateUser->setNacionalidad($user[4]);
        $updateUser->setFechaNacimiento(new \DateTime($year."-".$month."-".$day));
        $updateUser->setEdad($user[6]);
        $updateUser->setSexo($user[7]);
        $updateUser->setRif('J-'.$user[8]);
        $updateUser->setTelefono($user[9].'-'.$user[10]);
        $updateUser->setDireccion($user[15]);
        
        $em->flush();
    }
    
    private function updateSectionTwo($cargos,$email)
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
        
        $ufcs = $em->createQuery('SELECT ufc
                                 FROM RegistroUnicoBundle:UsuarioFechaCargo ufc
                                    INNER JOIN ufc.usuarios u
                                 WHERE u.id = :idUsuario')
                    ->setParameter('idUsuario',$user->getId())
                    ->getResult();
        foreach($ufcs as $ufc){
            $em->remove($ufc);
        }
        
        foreach($cargos as $cargo){
          $car = $em->getRepository('RegistroUnicoBundle:Cargo')
                    ->findOneByDescription($cargo['nombre']);
          $UsuarioFechaCargo = new UsuarioFechaCargo();
          list($day, $month, $year) = explode('/', explode(' ', $cargo['fechaInicio'])[0]);
          $UsuarioFechaCargo->setDate(new \DateTime($year."-".$month."-".$day));
        
          $user->addUsuarioFechaCargos($UsuarioFechaCargo);
          $car->addUsuarioFechaCargos($UsuarioFechaCargo);
        }
        $em->flush();
    }
    
    private function updateSectionThree($registros,$participantes,$revistas,$email)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Usuario')
                      ->findOneByCorreo($email);
    
        if (!$user) {
            throw $this->createNotFoundException(
                'Usuario no encontrado por el correo '.$email
            );
        }
        
        $regis = $user->getRegistrosAsObject();
        foreach($regis as $regi){
            $revis = $regi->getRevistasAsObject();
            $parts = $regi->getParticipantesAsObject();
            if($revis)
            {
                foreach($revis as $revi)
                {
                    $em->remove($revi);
                }
            }
            
            if($parts)
            {
                foreach($parts as $part)
                {
                    $em->remove($part);
                }
            }
            $em->remove($regi);
        }
        $em->flush();
        $em->clear();
        $this->registerSectionThree($registros,$participantes,$revistas,$email);
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
                    $newRevista->setVolumen($revista['volumen']);
                    $newRevista->setPrimeraUltimaPagina($revista['primerayUltimaPagina']);
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
            $newRegistro->setInstitucionEmpresaCasaeditorial($registro['empresaInstitucion']);
            $newRegistro->setDescription($registro['descripcion']);
            $newRegistro->setAño($registro['anio']);
            $newRegistro->setTituloObtenido($registro['tituloObtenido']);
            $newRegistro->setCongreso($registro['congreso']);
            $newRegistro->setCiudadPais($registro['ciudadPais']);
            $newRegistro->setIsValidate(false);
            
            if(in_array($registro['idRegistro'],$idsrevistas)){
                $pos = array_search($registro['idRegistro'],$idsrevistas);
                $newRegistro->addRevistas($revistass[$pos]);
            }
            if(in_array($registro['idRegistro'],$idsparticipantes)){
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
    
    private function updateSectionFour($hijos,$email,$input2bool,$input3bool)
    {
        $isHijo = false;
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Usuario')
                      ->findOneByCorreo($email);
    
        if (!$user) {
            throw $this->createNotFoundException(
                'Usuario no encontrado por el correo '.$email
            );
        }
        $recauds = $em->createQuery('SELECT r
                             FROM TramiteBundle:Recaudo r
                                INNER JOIN r.usuario u
                             WHERE u.id = :idUsuario')
                ->setParameter('idUsuario',$user->getId())
                ->getResult();
        $hijs = $user->getHijosAsObjects();
        $files = [];
        $files2 = [];
        $files3 = [];
        $i =0;
        if($recauds)
        {
            if(strcmp($input3bool,"false")==0 && strcmp($input2bool,"false")==0)
            {
                foreach($recauds as $recaud)
                {
                    if (copy($recaud->getPath(),explode(".pdf",$recaud->getPath())[0]."_copy.pdf")){
                        $isHijo = true;
                        $files[$i] = explode(".pdf",$recaud->getPath())[0]."_copy.pdf";
                        $i++;
                    }
                    unlink($recaud->getPath());
                    $em->remove($recaud);
                }
               
            }else
            {
                foreach($recauds as $recaud)
                {
                    if (strcmp($input3bool,"true")==0 &&  strcmp($recaud->getTabla(),"Hijo")==0 && copy($recaud->getPath(),explode(".pdf",$recaud->getPath())[0]."_copy.pdf")){
                        $isHijo = true;
                        $files[$i] = explode(".pdf",$recaud->getPath())[0]."_copy.pdf";
                        $i++;
                    }else if(strcmp($input2bool,"true")==0  &&  strcmp($recaud->getTabla(),"Usuario")==0 && copy($recaud->getPath(),explode(".pdf",$recaud->getPath())[0]."_copy.pdf")){
                        $files[$i] = explode(".pdf",$recaud->getPath())[0]."_copy.pdf";
                        $i++;
                    }
                    unlink($recaud->getPath());
                    $em->remove($recaud);
                }
            }
        }
        
        $i =0;
        foreach($files as $file)
        {
            rename($file, explode("_copy.pdf",$file)[0].".pdf");
            if(strcmp(explode('/',explode("_copy.pdf",$file)[0].".pdf")[count(explode('/',explode("_copy.pdf",$file)[0].".pdf"))-2],"hijos") == 0){
                $files2[$i] = explode("_copy.pdf",$file)[0].".pdf";
                $i++;
            }
        }
        if(strcmp($input3bool,"false")==0 && strcmp($input2bool,"false")==0)
        {
            $k = 0;
            foreach($files2 as $file2)
            {
                $files3[$k] = $file2;
                $k++;
            }
            $k=0;
            for($j = $i-1; $j >= 0; $j--)
            {
                $files2[$k] = $files3[$j];
                $k++;
            }
        }
        
        if($hijs)
        {
            foreach($hijs as $hij)
            {
                $em->remove($hij);
            }
        }
        $em->flush();
        $em->clear();
        $this->registerSectionFour($hijos,$email,$files2,$isHijo);
        $this->guardarUrlArchivosHijos($email);
    }
    
    private function registerSectionFour($hijos,$email,$files,$isHijo)
    {
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
        if($hijos == null)
            $hijos = [];
        foreach($hijos as $hijo){
             
             $newHijo = new Hijo();
             $newHijo->setCedulaMadre($hijo['ciMadre']);
             $newHijo->setCedulaPadre($hijo['ciPadre']);
             if($hijo['ciHijo'] == "")
                $newHijo->setCedulaHijo(null);
             else
                $newHijo->setCedulaHijo($hijo['ciHijo']);
             list($day, $month, $year) = explode('/', explode(' ', $hijo['fechaNacimiento'])[0]);
             $newHijo->setFechaNacimiento(new \DateTime($year."-".$month."-".$day));
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
             if(!$isHijo){
                $newRecaudo->setName("acta_nacimiento_".$newHijo->getId().".pdf");
             }else{
                $newRecaudo->setName(explode('/',$files[$i])[count(explode('/',$files[$i]))-1]);
             }
             list($day, $month, $year) = explode('/', explode(' ', $hijo['fechaVencimiento'])[0]);
             $newRecaudo->setFechaVencimiento(new \DateTime($year."-".$month."-".$day));
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
    
    private function burbuja($array, $array2)
    {
        for($i=1;$i<count($array);$i++)
        {
            for($j=0;$j<count($array)-$i;$j++)
            {
                if($array[$j]<$array[$j+1])
                {
                    $k=$array[$j+1];
                    $k2=$array2[$j+1];
                    $array[$j+1]=$array[$j];
                    $array2[$j+1]=$array2[$j];
                    $array[$j]=$k;
                    $array2[$j]=$k2;
                }
            }
        }
     
        return $array2;
    }

}