<?php

namespace TramiteBundle\Entity;
use Doctrine\ORM\EntityRepository;


class TransicionRepository extends EntityRepository
{
    public function getListadoAprobado()
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.estado = :estado')
            ->setParameter('estado', '4');

        return $qb->getQuery()
            ->getResult();

    }
}