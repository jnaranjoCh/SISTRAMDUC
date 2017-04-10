<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Usuario;
use AppBundle\Entity\Rol;
use PlanSeptenalBundle\Entity\PlanSeptenalIndividual;

class UsuarioTest extends \PHPUnit_Framework_TestCase
{
    public function testGetNombreCorto()
    {
        $tony = new Usuario();
        $tony->setPrimerNombre('Anthony');
        $tony->setPrimerApellido('Stark');

        $this->assertEquals('Anthony Stark', $tony->getNombreCorto());
    }

    public function testGetNombreCortoShouldNotAddNeedlessSpacing()
    {
        $tony = new Usuario();
        $tony->setPrimerNombre('Anthony');
        $this->assertEquals('Anthony', $tony->getNombreCorto());

        $tony->setPrimerNombre('');
        $tony->setPrimerApellido('Stark');
        $this->assertEquals('Stark', $tony->getNombreCorto());
    }

    public function testGetNombreCompleto()
    {
        $tony = new Usuario();
        $tony->setPrimerNombre('Anthony');
        $tony->setSegundoNombre('Edward');
        $tony->setPrimerApellido('Stark');
        $tony->setSegundoApellido('-');

        $this->assertEquals('Anthony Edward Stark -', $tony->getNombreCompleto());
    }

    /**
     * @dataProvider usersWithMissingNamesProvider
     */
    public function testGetNombreCompletoShouldNotAddNeedlessSpacing($usuario, $expectedNombreCompleto)
    {
        $this->assertEquals($expectedNombreCompleto, $usuario->getNombreCompleto());
    }

    public function usersWithMissingNamesProvider()
    {
        $data['All missing'] = [ (new Usuario())->setNombreCompleto(), "" ];
        $data['Missing primer nombre'] = [ (new Usuario())->setNombreCompleto(null, 'Edward', 'Stark', '-'), 'Edward Stark -' ];
        $data['Missing segundo nombre'] = [ (new Usuario())->setNombreCompleto('Anthony', null, 'Stark', '-'), 'Anthony Stark -' ];
        $data['Missing primer apellido'] = [ (new Usuario())->setNombreCompleto('Anthony', 'Edward', null, '-'), 'Anthony Edward -' ];
        $data['Missing segundo apellido'] = [ (new Usuario())->setNombreCompleto('Anthony', 'Edward', 'Stark', null), 'Anthony Edward Stark' ];

        return $data;
    }

    public function testAddRol()
    {
        $superhero = (new Rol())->setNombre('superhero');

        $tony = new Usuario();
        $tony->addRol($superhero);

        $this->assertContains('superhero', $tony->getRoles());
    }
}
