<?php
/**
 * Created by IntelliJ IDEA.
 * User: sheitan666
 * Date: 20/12/2016
 * Time: 13:20
 */

namespace VoyageBundle\Repository;

use Doctrine\ORM\EntityRepository;

class VoyagesRepository extends EntityRepository
{

    public function findLastTrips(){
        $qb = $this->createQueryBuilder('v')
            ->select('v')
            ->orderBy('v.updatedAt','DESC')
            ->setMaxResults(3);

        return $qb->getQuery()->getResult();
    }

    public function countTrips(){
        $qb = $this->createQueryBuilder('v')
            ->select('count(v)');

        return $qb->getQuery()->getSingleScalarResult();
    }



}