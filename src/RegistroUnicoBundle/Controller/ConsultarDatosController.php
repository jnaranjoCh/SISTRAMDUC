<?php

namespace RegistroUnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use RegistroUnicoBundle\Entity\Estatus;
use RegistroUnicoBundle\Entity\Nivel;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Rol;
use \stdClass;

class ConsultarDatosController extends Controller
{
    
    public function consultarRegistroAction()
    {
        return $this->render('RegistroUnicoBundle:ConsultarDatos:consultar_registro.html.twig');
    }
    
    public function enviarEmailsAjaxAction(Request $request)
    {
        return new JsonResponse($this->getEmails($this->getAll("AppBundle:","Usuario")));
    }   
    
    public function enviarRegistrosDeUsuarioAjaxAction(Request $request)
    {
        $data = $this->getDoctrine()
                      ->getManager()
                      ->createQuery('SELECT u,r FROM AppBundle:Usuario u JOIN u.registros r WHERE u.correo = :email')
                      ->setParameter('email',$request->get('email'))
                      ->getResult()[0]->getRegistros();
                      
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
            $data = $data[0]->getRegistrosParticipantes();
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
            $data = $data[0]->getRegistrosRevistas();
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
            $files = null;
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
                $data[$i]['Delete'] = "<img src='/web/assets/images/delete.png' width='30px' heigth='30px'/>";
                $data[$i]['CIMadre'] = '<input id="CIMadre'.$i.'" value="'.$hijo['cedulaMadre'].'" type="number" class="form-control" placeholder="Cedula Madre">';
                $data[$i]['CIPadre'] = '<input id="CIPadre'.$i.'" value="'.$hijo['cedulaPadre'].'" type="number" class="form-control" placeholder="Cedula Padre">';
                $data[$i]['CIHijo'] = '<input id="CIHijo'.$i.'" value="'.$hijo['cedulaHijo'].'" type="number" class="form-control" placeholder="Cedula Hijo">';
                $data[$i]['1erNombre'] = '<input id="1erNombre'.$i.'" value="'.$hijo['primerNombre'].'" type="text" class="form-control" placeholder="Primer Nombre">';
                $data[$i]['2doNombre'] = '<input id="2doNombre'.$i.'" value="'.$hijo['segundoNombre'].'" type="text" class="form-control" placeholder="Segundo Nombre">';
                $data[$i]['1erApellido'] = '<input id="1erApellido'.$i.'" value="'.$hijo['primerApellido'].'" type="text" class="form-control" placeholder="Primer Apellido">';
                $data[$i]['2doApellido'] = '<input id="2doApellido'.$i.'" value="'.$hijo['segundoApellido'].'" type="text" class="form-control" placeholder="Segundo Apellido">';
                $data[$i]['FNacimiento'] = "<div class='row'>
                                              <div class='col-xs-12'>
                                                <div class='form-group has-feedback'>
                                                    <div class='input-group date'>
                                                        <input id='datepickerHijo1".$i."' value='".$hijo['fechaNacimiento']->format('d/m/Y H:i')."' name='FNacimiento".$i."' type='text' class='form-control' style='width: 240px;'/>
                                                        <span class='input-group-addon'>
                                                            <span class='glyphicon glyphicon-calendar'></span>
                                                        </span>
                                                    </div>
                                                </div>
                                              </div>
                                           </div>";
                foreach($files as $file)
                {
                    if($file['path'] == $hijo['partidaNacimientoUrl'])
                        $data[$i]['FVencimientoActa'] = "<div class='row'>
                                                          <div class='col-xs-12'>
                                                            <div class='form-group has-feedback'>
                                                                <div class='input-group date'>
                                                                    <input id='datepickerHijo2".$i."' value='".$file['fecha_vencimiento']->format('d/m/Y H:i')."' name='FVencimientoActa".$i."' type='text' class='form-control' style='width: 200px;'/>
                                                                    <span class='input-group-addon'>
                                                                        <span class='glyphicon glyphicon-calendar'></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                          </div>
                                                       </div>";
                }
                $data[$i]['Nacionalidad'] = '<input id="Nacionalidad'.$i.'" value="'.$hijo['nacionalidad'].'" type="text" class="form-control" placeholder="Nacionalidad">';
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
                $data[$i]['Delete'] = "<img src='/web/assets/images/delete.png' width='30px' heigth='30px'/>";
                $data[$i]['Cargo'] = $htmlCargos; 
                $data[$i]['FechaDeInicioEnElCargo'] = "<div class='row'>
                                                          <div class='col-xs-12'>
                                                            <div class='form-group has-feedback'>
                                                                <div class='input-group date'>
                                                                    <input id='datepicker".$i."' value='".$cargoDate['date']->format('d/m/Y H:i')."' name='FechaInicioCargoDatos".$i."' type='text' class='form-control'/>
                                                                    <span class='input-group-addon'>
                                                                        <span class='glyphicon glyphicon-calendar'></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                          </div>
                                                       </div>";
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
        foreach($object as $value)
        {
            if($value->getIsRegister())
            {
               $data["Email"] = $value->getCorreo();
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
}