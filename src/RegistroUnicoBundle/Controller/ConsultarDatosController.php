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