<?php
/**
 * Created by IntelliJ IDEA.
 * User: sheitan666
 * Date: 20/12/2016
 * Time: 13:20
 */

namespace VoyageBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DestinationRepository extends EntityRepository
{
    public function getCountriesByString($string)
    {
        $qb = $this->createQueryBuilder('d')
            ->select('d')
            ->distinct('d.pays')
            ->where('d.pays LIKE :string')
            ->setParameter('string', '%'.$string.'%')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }
    public function getPlacesByString($string)
    {
        $qb = $this->createQueryBuilder('d')
            ->select('d')
            ->distinct(true)
            ->where('d.nomdestination LIKE :string')
            ->setParameter('string', '%'.$string.'%')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }
}