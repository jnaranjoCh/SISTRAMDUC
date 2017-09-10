<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use AppBundle\Entity\Usuario;
use RegistroUnicoBundle\Entity\UsuarioFechaCargo;
use RegistroUnicoBundle\Entity\Registro;
use ClausulasContractualesBundle\Entity\Hijo;
use TramiteBundle\Entity\Recaudo;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $tony = new Usuario();
        $tony->setCedula('1234');
        $tony->setEdad(0);
        $tony->setSexo('');
        $tony->setPrimerNombre('Anthony');
        $tony->setSegundoNombre('Edward');
        $tony->setPrimerApellido('Stark');
        $tony->setSegundoApellido(' ');
        $tony->setNacionalidad('_');
        $tony->setCorreo('tony@stark.com');
        $tony->setTelefono('');
        $tony->setRif('');
        $tony->setActivo(true);
        $tony->setIsRegister(true);
        $tony->setDireccion('Malibu');

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($tony, '1234');
        $tony->setPassword($password);

        $tony->addRol($this->getReference('administrador-rol'));
        $tony->addRol($this->getReference('profesor-rol'));

        $manager->persist($tony);
        
        $this->addReference('tony-user', $tony);
        $manager->flush();
        
                      
        $UsuarioFechaCargo = new UsuarioFechaCargo();
        $UsuarioFechaCargo->setDate(new \DateTime('19-09-2016'));
        
        $car = $this->getReference('Administrativo-cargo');
        $tony->addUsuarioFechaCargos($UsuarioFechaCargo);
        $car->addUsuarioFechaCargos($UsuarioFechaCargo);
        $manager->flush();
        
        $newRegistro =  new Registro();
        $newRegistro->setTipo($this->getReference('Estudio-tipoRegistro'));
        $newRegistro->setNivel($this->getReference('Curso-nivel'));
        $newRegistro->setEstatus($this->getReference('Iniciciando-estatus'));
        $newRegistro->setInstitucionEmpresaCasaeditorial('institucion');
        $newRegistro->setDescription('estudio');
        $newRegistro->setAño(1222);
        $newRegistro->setTituloObtenido('');
        $newRegistro->setCongreso('');
        $newRegistro->setCiudadPais('');
        $newRegistro->setIsValidate(false);
        $manager->persist($newRegistro);
        $manager->flush();
        $this->addReference('tony-registro', $newRegistro);
        
        $newHijo = new Hijo();
        $newHijo->setCedulaMadre(6879538);
        $newHijo->setCedulaPadre(6422928);
        $newHijo->setCedulaHijo(23795053);
        $newHijo->setFechaNacimiento(new \DateTime('19-09-2016'));
        $newHijo->setPrimerNombre('jose');
        $newHijo->setSegundoNombre('angel');
        $newHijo->setPrimerApellido('contreras');
        $newHijo->setSegundoApellido('rengifo');
        $newHijo->setNacionalidad('venezolano');
        $newHijo->setPartidaNacimientoUrl($this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/acta_nacimiento/hijos/ejemplo.pdf');
        $manager->persist($newHijo);
        $manager->flush();
        $this->addReference('tony-hijo', $newHijo);
        
        $newRecaudo = new Recaudo();
        $tipo_recaudo = $this->getReference('Cedula-tipoRecaudo');
        $newRecaudo->setPath($this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/cedula/users/ejemplo.pdf');
        $newRecaudo->setName('ejemplo.pdf');
        $newRecaudo->setFechaVencimiento(new \DateTime('19-09-2016'));
        $newRecaudo->setUsuario($this->getReference('tony-user'));
        $newRecaudo->setTipoRecaudo($tipo_recaudo);
        $newRecaudo->setTabla("Usuario");
        $manager->persist($newRecaudo);
        $manager->flush();
        $this->addReference('tony-recaudo-cedula', $newRecaudo);
        
        $newRecaudo = new Recaudo();
        $tipo_recaudo = $this->getReference('RIF-tipoRecaudo');
        $newRecaudo->setPath($this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/RIF/users/ejemplo.pdf');
        $newRecaudo->setName('ejemplo.pdf');
        $newRecaudo->setFechaVencimiento(new \DateTime('19-09-2016'));
        $newRecaudo->setUsuario($this->getReference('tony-user'));
        $newRecaudo->setTipoRecaudo($tipo_recaudo);
        $newRecaudo->setTabla("Usuario");
        $manager->persist($newRecaudo);
        $manager->flush();
        $this->addReference('tony-recaudo-RIF', $newRecaudo);
        
        $newRecaudo = new Recaudo();
        $tipo_recaudo = $this->getReference('Partida de Nacimiento-tipoRecaudo');
        $newRecaudo->setPath($this->container->getParameter('kernel.root_dir').'/../web/uploads/recaudos/acta_nacimiento/users/ejemplo.pdf');
        $newRecaudo->setName('ejemplo.pdf');
        $newRecaudo->setFechaVencimiento(new \DateTime('19-09-2016'));
        $newRecaudo->setUsuario($this->getReference('tony-user'));
        $newRecaudo->setTipoRecaudo($tipo_recaudo);
        $newRecaudo->setTabla("Usuario");
        $manager->persist($newRecaudo);
        $manager->flush();
        $this->addReference('tony-recaudo-Partida de Nacimiento', $newRecaudo);
    }

    public function getOrder()
    {
        return 2;
    }
}
