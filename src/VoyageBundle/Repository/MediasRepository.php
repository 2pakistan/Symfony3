<?php
/**
 * Created by IntelliJ IDEA.
 * User: sheitan666
 * Date: 20/12/2016
 * Time: 13:20
 */

namespace VoyageBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MediasRepository extends EntityRepository
{

    function findByStep($step){
        $qb = $this->createQueryBuilder('m')
            ->select('m.pathMedia')
            ->join('m.idetape' ,'e')
            ->where('e.trip = :trip')
            ->setParameter('trip', $step->getTrip())
            ->andWhere('e.idetape = :step')
            ->setParameter('step', $step);

        return $qb->getQuery()->getResult();
    }

}