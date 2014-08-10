<?php
/**
 * Created by PhpStorm.
 * User: mteixeira
 * Date: 8/7/14
 * Time: 9:57 AM
 */

namespace Anuncios\Model;


class GenericModel
{
    private $entityManager;
    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $queryBuilder ;


    /**
     * @param mixed $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param mixed $queryBuilder
     */
    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @return mixed
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }


} 