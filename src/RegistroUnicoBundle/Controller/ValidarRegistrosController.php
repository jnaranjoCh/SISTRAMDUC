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

class ValidarRegistrosController extends Controller
{

    public function validarRegistrosAction()
    {
        return $this->render('RegistroUnicoBundle:ValidarRegistros:validar_registros.html.twig');
    }
    
    public function mostrarIframeAction()
    {
        return $this->render('RegistroUnicoBundle:ValidarRegistros:modal_para_enviar_archivo.html.twig');
    }
    public function enviarRegistrosAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $data = $this->getDoctrine()
                         ->getManager()
                         ->createQuery('SELECT u,r FROM AppBundle:Usuario u JOIN u.registros r WHERE u.correo = :email')
                         ->setParameter('email',$request->get('email'))
                         ->getResult()[0]->getRegistrosValidate();
                      
            return new JsonResponse( array(
                            "draw"            => 1,
                	        "recordsTotal"    => $data->num,
                	        "recordsFiltered" => $data->num,
                	        "data"            => $data->data 
                        ));
        }
        else
            return new JsonResponse("Error");
    }
    
    public function actualizarRegistrosAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $this->actualizarRegistros($request->get('Registros'));
            return new JsonResponse("Actualizado");
        }
        else
            return new JsonResponse("Error");
    }
    
    private function actualizarRegistros($registros)
    {
        $em = $this->getDoctrine()->getManager();
        foreach($registros as $registro)
        {
            $registroActualizar = $em->getRepository('RegistroUnicoBundle:Registro')
                                     ->findOneById($registro['idRegistro']);
            $registroActualizar->setIsValidate($registro['validado']);
            $em->flush();
        }
        
    }
    
    public function validarArchivosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $registro = $em->getRepository('RegistroUnicoBundle:Registro')
                       ->findOneById($_POST['idDelRegistro']);
     
        $user = $em->getRepository('AppBundle:Usuario')
                   ->findOneByCorreo($_POST['EmailDelRegistro']);
                   
        $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/registros/';
        $fichero_subido = $dir_subida.$registro->getDescription().'_'.$user->getCedula().".pdf";
        
        if(move_uploaded_file($_FILES['input3']['tmp_name'][0], $fichero_subido)) {
            $registro->setUrl($fichero_subido);
            $em->flush();
        }       
        return $this->render('RegistroUnicoBundle:ValidarRegistros:modal_para_enviar_archivo.html.twig');
    }
}