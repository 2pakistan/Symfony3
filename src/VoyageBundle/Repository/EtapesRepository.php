<?php
/**
 * Created by IntelliJ IDEA.
 * User: sheitan666
 * Date: 20/12/2016
 * Time: 13:20
 */

namespace VoyageBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EtapesRepository extends EntityRepository
{

    function getNbStepsTrip($trip){
        $qb = $this->createQueryBuilder('e')
            ->select('count(e)')
            ->where('e.trip = :id')
            ->setParameter('id', $trip);

        return $qb->getQuery()->getSingleScalarResult();
    }
}