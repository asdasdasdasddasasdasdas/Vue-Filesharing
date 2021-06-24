<?php


namespace Test\validator;


use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class Validator
{

    private $errors;
    /**
     * @var EntityManager
     */
    private $em;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function hasErrors()
    {
        return $this->errors !== null;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function validate($params)
    {

        foreach ($params as $key => $param) {

            foreach ($param["asserts"] as $assertName => $assertValue) {
                $assertParams = [];
                $explodedParams = explode("|", $assertValue);

                foreach ($explodedParams as $assertParam) {

                    $data = explode(":", $assertParam);
                    $assertParams[$data[0]] = isset($data[1]) ? $data[1] : null;
                }


                if (!method_exists($this, $assertValue) && !method_exists($this, $assertName)) continue;


                if (is_int($assertName)) {

                    $result = $this->$assertValue($param["value"]);

                    if ($result) $this->errors[$key][$assertValue] = $result;

                } else {

                    if (isset($assertParams["param"])) {
                        $result = $this->$assertName($assertParams["param"], $param["value"]);
                    } else {

                        $result = $this->$assertName($param["value"]);
                    }
                    $message = $assertParams["message"] ?? "Error";
                    if ($result) $this->errors[$key][$assertName] = $message;
                };

            }
        }
    }

    public function integer($field)
    {
        return !is_int($field);


    }

    public function equal($field, $value)
    {
        return !($field === $value);
    }

    public function email($field)
    {

        return !(filter_var($field, \FILTER_VALIDATE_EMAIL) ? true : false);
    }

    public function lengthMin(string $field, $value)
    {

        return !($field < strlen($value));


    }

    public function lengthMax(string $field, $value)
    {
        return !($field > strlen($value));


    }

    public function unique(string $field, $value)
    {
        $query = "SELECT COUNT(u." . $field . ") FROM Test\models\User u WHERE u." . $field . " = :value";
        return $this->em->createQuery($query)->setParameter(":value", $value)->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR) ? true : false;
    }

    public function min(int $field, $value)
    {
        return !($field > $value);

    }

    public function max(int $field, $value)
    {
        return !($field > $value);

    }

    public function string($field)
    {
        return !is_string($field);
    }

    public function blank($field)
    {
        return !(strlen($field)>0);
    }
    public function null($field){
        return ($field == null);
    }

}