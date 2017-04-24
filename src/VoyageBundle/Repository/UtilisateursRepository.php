<?php
/**
 * Created by IntelliJ IDEA.
 * User: sheitan666
 * Date: 20/12/2016
 * Time: 13:20
 */

namespace VoyageBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UtilisateursRepository extends EntityRepository
{

    //Function qui retourne les 3 derniers utilisateurs inscrits.
    public function findLastRegistered(){
        $qb = $this->createQueryBuilder('u')
                        ->select('u')
                        ->orderBy('u.createdAt','DESC')
                        ->setMaxResults(4);

        return $qb->getQuery()->getResult();
    }
    //Function qui retourne les 3 derniers avis publiés
    public function findLastReviews(){
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->orderBy('u.reviewedAt','DESC')
            ->setMaxResults(3);

        return $qb->getQuery()->getResult();
    }

    public function findTravellersByVoyage($voyage)
    {
        $query = $this->createQueryBuilder('u')
            ->select('u')
            ->leftJoin('u.voyages', 'v')
            ->addSelect('v');

        $query = $query->add('where', $query->expr()->in('v', ':v'))
            ->setParameter('v',$voyage )
            ->getQuery()
            ->getResult();

        return $query;
    }


}