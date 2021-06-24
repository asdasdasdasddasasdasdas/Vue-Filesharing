<?php


namespace Test\Repositories;


use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Tools\Pagination\Paginator;

class FileRepository extends \Doctrine\ORM\EntityRepository
{





    public function getById($id)
    {
            return $file = $this->_em->createQuery("SELECT f, uf.name FROM Test\models\File f LEFT JOIN f.user uf WHERE f.id = :id")->setParameter(":id", $id)->getResult(AbstractQuery::HYDRATE_ARRAY);
    }



    public function getByQuery($search,$type,$firstResult,$maxResult)
    {
           return $this->_em->createQuery("SELECT f FROM Test\models\File f WHERE f.name LIKE :search and f.type LIKE :type ORDER BY f.created DESC")
            ->setParameter(":search", $search)
            ->setParameter(":type", $type)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult);
    }

}