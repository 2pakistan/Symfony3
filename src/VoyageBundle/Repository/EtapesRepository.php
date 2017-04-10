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

    function getNbStepsByCountry($country){
        $qb = $this->createQueryBuilder('e')
            ->select('count(e)')
            ->join('e.country' ,'c')
            ->where('c.id =  e.country')
            ->andWhere('e.country = :country')
            ->setParameter('country', $country);
        return $qb->getQuery()->getSingleScalarResult();
    }

    //Find steps by array of trips
    function getRecentByTrips($trips){
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.trip IN (:trips)')
            ->orderBy('e.idetape', 'DESC')
            ->setParameter('trips', $trips)
            ->setMaxResults(8);
        return $qb->getQuery()->getResult();
    }

    function getStepsByPlaceString($string){
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->join('e.country' ,'co')
            ->join('e.cities' ,'ci')
            ->join('e.state' ,'s')
            ->where('co.name LIKE :string')
            ->orWhere('ci.name LIKE :string')
            ->orWhere('s.name LIKE :string')
            ->setParameter('string', '%'.$string.'%');
        return $qb->getQuery()->getResult();
    }

    function getStepsByCountryString($string){
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->distinct('e.country')
            ->join('e.country' ,'c')
            ->where('c.name LIKE :string')
            ->setParameter('string', '%'.$string.'%');
        return $qb->getQuery()->getResult();
    }

    function getStepsByStateString($string){
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->join('e.state' ,'s')
            ->orWhere('s.name LIKE :string')
            ->setParameter('string', '%'.$string.'%');
        return $qb->getQuery()->getResult();
    }

    function getStepsByCityString($string){
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->join('e.cities' ,'ci')
            ->orWhere('ci.name LIKE  :string')
            ->setParameter('string', '%'.$string.'%');
        return $qb->getQuery()->getResult();
    }

}