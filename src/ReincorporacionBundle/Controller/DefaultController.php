<?php

namespace ReincorporacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use ReincorporacionBundle\Entity\TramiteReincorporacion;
use TramiteBundle\Entity\Transicion;
use TramiteBundle\Entity\Estado;

class DefaultController extends Controller
{
    /**
     * @Route("/reincorporacion-docente", name="reincorporacion")
     */
    public function mostrarInicioDeSolicitud()
    {
        return $this->render('ReincorporacionBundle::inicio-solicitud.html.twig');
    }
    
    /**
     * @Route("/reincorporacion-docente/actualizar-curriculum", name="actualizar-curriculum")
     */ 
    public function mostrarActualizarCurriculum()
    {
        return $this->render('ReincorporacionBundle::actualizar-curriculum.html.twig');
    }
    
    /**
     * @Route("/reincorporacion-docente/actualizar-curriculum/nueva-entrada", name="nueva-entrada-curriculum")
     */ 
    public function mostrarCrearEntradaEnCurriculum()
    {
        // Creacion de form aqui!
        return $this->render('ReincorporacionBundle::nueva-entrada-curriculum.html.twig');
    }
    

    /**
     * @Route("/reincorporacion-docente/subir-recaudos", name="subir-recaudos")
     */ 
    public function mostrarSubirRecaudos(Request $request)
    {
        $tramite_reincorporacion = new TramiteReincorporacion();
        $form = $this->createForm('ReincorporacionBundle\Form\TramiteReincorporacionType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /* Se obtienen todos los registros de la tabla tipo_tramite y se busca el registro que 
            corresponda a Reincorporacion */
            $tipo_tramite_repo = $em->getRepository(TipoTramite::class);
            $tipo_tramite = $tipo_tramite_repo->findOneBy(["id" => 3]);
            $lista_recaudos = $em->getRepository(TipoRecaudo::class)
                                 ->findById(array(11,12,13,14,15,16));

            var_dump($lista_recaudos);

            // $recaudo1 = $lista_recaudos[0];
            // $recaudo2 = $lista_recaudos[1];
            // $recaudo3 = $lista_recaudos[2];
            // $recaudo4 = $lista_recaudos[3];
            // $recaudo5 = $lista_recaudos[4];
            // $recaudo6 = $lista_recaudos[5];

            $i = 0;
            foreach($tramite_reincorporacion->getRecaudos() as $recaudo) {
                $recaudo->setTipoRecaudo($lista_recaudos[$i]);
            }

            $tramite_reincorporacion->assingTo($this->getUser())
                                    ->setTipoTramite($tipo_tramite);

            $em->persist($tramite_reincorporacion);

            foreach($tramite_reincorporacion->getRecaudos() as $recaudo) {
                $recaudo->setTramite($tramite_reincorporacion);
            }

            $em->flush;

            $this->mostrarVerificarDatos($tramite_reincorporacion);
        }

        // $designacion_docente = $request->files->get('designacion_docente');
        // $oficio_ubicacion = $request->files->get('oficio_ubicacion');
        // $ultimo_ascenso = $request->files->get('ultimo_ascenso');
        // $aceptacion_renuncia = $request->files->get('aceptacion_renuncia');
        // $titulo = $request->files->get('titulo');
        // $declaracion_jurada = $request->files->get('declaracion_jurada');

        // $status = array('status' => "success", "fileUploaded" => false);

        // if (!is_null($designacion_docente) && !is_null($oficio_ubicacion) && !is_null($ultimo_ascenso) && !is_null($aceptacion_renuncia) && !is_null($titulo) && !is_null($declaracion_jurada)) {
        //     $dir_docs = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/reincorporacion';
        //     $dir_subida = $this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/acta_nacimiento/hijos/';
        //         $fichero_subido = $dir_subida.$recaudo->getName();
        // }

        return $this->render('ReincorporacionBundle::subir-recaudos.html.twig', array(
            'tramiteReincorporacion' => $tramite_reincorporacion,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/reincorporacion-docente/verificar-datos", name="verificar-datos")
     */ 
    public function mostrarVerificarDatos(TramiteReincorporacion $t)
    {        
        return $this->render('ReincorporacionBundle::verificar-datos.html.twig', array('id' => $t->getId()));
    }

    
     
}
