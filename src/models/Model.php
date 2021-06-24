<?php


namespace Test\models;


class Model
{
    private $em;

    /**
     * Model constructor.
     * @param $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    public  function save(){
        $this->em->flush();
    }
}