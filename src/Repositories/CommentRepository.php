<?php


namespace Test\Repositories;


use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function getCommentsByFile($id,$firstResult)
    {
         return $this->_em->createQuery("SELECT  c,u.name, u.avatarPath  FROM Test\models\Comment c JOIN c.user u WHERE c.file = :id and c.parent IS NULL ORDER BY c.created DESC")
             ->setParameter(":id",$id)
             ->setFirstResult($firstResult)
             ->setMaxResults(10)
             ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
    public function getNewFileComments($id, $date) :array
    {
        return $this->_em->createQuery("SELECT c,u.name,u.avatarPath FROM Test\models\Comment c LEFT join c.user u WHERE c.file = :id and c.created >= :date and c.parent IS NULL ORDER BY c.created DESC")->setParameter(":id", $id)->setParameter(":date", $date)->getResult(AbstractQuery::HYDRATE_ARRAY);
    }

    public function getCommentsChildren($id, $firstResult) :array
    {
        return $this->_em->createQuery("SELECT c,u.name,u.avatarPath FROM Test\models\Comment c LEFT join c.user u WHERE c.parent = :id ORDER BY c.created desc")
            ->setParameter(":id", $id)
            ->setFirstResult($firstResult)
            ->setMaxResults(10)
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
    public function getNewCommentsChild($id, $date) :array
    {
        return $this->_em->createQuery("SELECT c,u.name,u.avatarPath FROM Test\models\Comment c LEFT join c.user u WHERE c.parent = :id and c.created >= :date ORDER BY c.created DESC")->setParameter(":id", $id)->setParameter(":date", $date)->getResult(AbstractQuery::HYDRATE_ARRAY);
    }


    public function countCommentsChildren($id){
        return $this->_em->createQuery("SELECT count(c) FROM Test\models\Comment c WHERE c.parent = :id")
            ->setParameter(":id", $id)
            ->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);;
    }
}