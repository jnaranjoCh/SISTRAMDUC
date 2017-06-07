<?php

namespace ComisionRemuneradaBundle\Entity;
use Doctrine\ORM\EntityRepository;


class SolicitudComisionServicioRepository extends EntityRepository
{
    public function findByUsuario ($usuario_id){
        $this->findOneBy(
            array('usuario_id'=>$usuario_id),
            array('id' => 'DESC'));
        return $this;
    }
}