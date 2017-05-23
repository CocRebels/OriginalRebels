<?php
/**
 * Created by PhpStorm.
 * User: fullcontroll
 * Date: 17.5.21
 * Time: 11.57
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CountriesDataRepository extends EntityRepository
{
    public function getCountryByKey($key)
    {
        return $this->createQueryBuilder('c')
            ->where('c.shortName = :key')
            ->setParameter('key', $key)
            ->getQuery()
            ->getOneOrNullResult();
    }
}