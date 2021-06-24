<?php


namespace Test\Repositories;


use Doctrine\ORM\AbstractQuery;

class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUserByHash($hash,$hydratation_type = 1)
    {

        return $this->_em->createQuery("SELECT u FROM Test\models\User u WHERE u.hash = :hash")
            ->setParameter(":hash", $hash ?? null)
            ->getResult($hydratation_type);
    }

    public function login($data)
    {
       return $this->_em->createQuery("SELECT u.hash FROM Test\models\User u WHERE u.email = :email and u.password = :password")
            ->setParameters([
                ":email" => $data["email"] ?? null,
                ":password" => $data["password"] ?? null
            ])->getResult();
    }
    public function getAnonimUser()
    {
        return $this->_em->createQuery("SELECT u  FROM Test\models\User u WHERE u.hash = 'anonim'")
           ->getResult();
    }
    public  function getUserById($id)
    {
        return $this->_em->createQuery("SELECT u.name, u.created, u.avatarPath FROM Test\models\User u WHERE u.id = :id")->setParameter("id",$id)->getResult(2);
    }
}