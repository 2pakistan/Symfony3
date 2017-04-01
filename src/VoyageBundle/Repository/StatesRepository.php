<?php

namespace VoyageBundle\Repository;

use Doctrine\ORM\EntityRepository;

class StatesRepository extends EntityRepository
{
    public function getStatesByString($string)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->distinct('s.name')
            ->where('s.name LIKE :string')
            ->setParameter('string', '%'.$string.'%')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

}