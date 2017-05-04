<?php

namespace VoyageBundle\Repository;

use Doctrine\ORM\EntityRepository;
use VoyageBundle\Entity\Utilisateurs;


class CountriesRepository extends EntityRepository
{

    public function getCountriesByString($string)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->distinct('c.name')
            ->where('c.name LIKE :string')
            ->setParameter('string', '%'.$string.'%')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }


}