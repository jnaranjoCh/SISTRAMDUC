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

    public function getListadoAprobadosNegados()
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.estado = :estado1 OR t.estado = :estado2')
            ->setParameter('estado1', '2')
            ->setParameter('estado2', '3');

        return $qb->getQuery()
            ->getResult();

    }
}