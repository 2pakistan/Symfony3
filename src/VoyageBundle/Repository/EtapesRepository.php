<?php
/**
 * Created by IntelliJ IDEA.
 * User: sheitan666
 * Date: 20/12/2016
 * Time: 13:20
 */

namespace VoyageBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use VoyageBundle\Entity\Countries;
use VoyageBundle\Entity\Utilisateurs;
use VoyageBundle\Entity\Voyages;

class EtapesRepository extends EntityRepository
{

    function findAllByDate(){

        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->orderBy('e.createDate', 'DESC');

        return $qb->getQuery()->getResult();
    }

    function getNbStepsTrip($trip)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('count(e)')
            ->where('e.trip = :id')
            ->setParameter('id', $trip);

        return $qb->getQuery()->getSingleScalarResult();
    }

    function getNbStepsByCountry(Countries $country, Voyages $trip)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('count(e)')
            ->innerJoin('e.country', 'c')
            ->innerJoin('e.trip', 't')
            ->where('c = :country')
            ->andWhere('t = :trip')
            ->setParameter('country', $country)
            ->setParameter('trip', $trip);
        return $qb->getQuery()->getSingleScalarResult();
    }
    function getAllNbStepsByCountry(Utilisateurs $user, Countries $country)
    {
        $v = $user->getVoyages();
        $qb = $this->createQueryBuilder('e')
            ->select('count(e)')
            ->innerJoin('e.country', 'c')
            ->innerJoin('e.trip', 't')
            ->where('c.id = :country')
            ->andWhere('t.idvoyage IN( :trips )')
            ->setParameter('country', $country->getId())
            ->setParameter('trips', $v);

        return $qb->getQuery()->getSingleScalarResult();
    }

    //Find steps by array of trips
    function getRecentByTrips($trips)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.trip IN (:trips)')
            ->orderBy('e.idetape', 'DESC')
            ->setParameter('trips', $trips)
            ->setMaxResults(8);
        return $qb->getQuery()->getResult();
    }

    function getStepsByPlaceString($string)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->leftJoin('e.country', 'co')
            ->leftJoin('e.cities', 'ci')
            ->leftJoin('e.state', 's')
            ->where('co.name LIKE :string')
            ->orWhere('ci.name LIKE :string')
            ->orWhere('s.name LIKE :string')
            ->setParameter('string', '%' . $string . '%');

        return $qb->getQuery()->getResult();
    }

    function getStepsByCountryString($string)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->distinct('e.country')
            ->join('e.country', 'c')
            ->where('c.name LIKE :string')
            ->setParameter('string', '%' . $string . '%');
        return $qb->getQuery()->getResult();
    }

    function getStepsByStateString($string)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->join('e.state', 's')
            ->orWhere('s.name LIKE :string')
            ->setParameter('string', '%' . $string . '%');
        return $qb->getQuery()->getResult();
    }

    function getStepsByCityString($string)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->join('e.cities', 'ci')
            ->orWhere('ci.name LIKE  :string')
            ->setParameter('string', '%' . $string . '%');
        return $qb->getQuery()->getResult();
    }

    function getNbStepsUnseenSince($date, array $trips)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('count(e)')
            ->join('e.trip', 't')
            ->where('e.createDate >= :date')
            ->andWhere('t IN (:trips)')
            ->setParameter('trips', $trips)
            ->setParameter('date', $date);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getUserSteps(Utilisateurs $user)
    {
        $trips = $user->getVoyages();
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->join('e.trip', 't')
            ->where('t IN (:trips)')
            ->setParameter('trips', $trips);

        return $qb->getQuery()->getResult();
    }

    public function getCountriesVisitedByUser(Utilisateurs $user)
    {
        $userSteps = $this->getUserSteps($user);

        $qb = $this->createQueryBuilder('e')
            ->select('c')
            ->from('VoyageBundle:Countries', 'c')
            ->innerJoin('e.country', 'ec')
            ->where('e IN (:userSteps)')
            ->andWhere('ec = c')
            ->setParameter('userSteps', $userSteps);

        return $qb->getQuery()->getResult();
    }

    public function countCountriesVisitedByUser(Utilisateurs $user)
    {
        $userSteps = $this->getUserSteps($user);

        $qb = $this->createQueryBuilder('e')
            ->select('count(distinct c)')
            ->from('VoyageBundle:Countries', 'c')
            ->innerJoin('e.country', 'ec')
            ->where('e IN (:userSteps)')
            ->andWhere('ec = c')
            ->setParameter('userSteps', $userSteps);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countSteps(){
        $qb = $this->createQueryBuilder('e')
            ->select('count( e)');


        return $qb->getQuery()->getSingleScalarResult();
    }




}