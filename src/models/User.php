<?php


namespace Test\models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Test\Repositories\UserRepository")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", unique=true, nullable = false)
     */

    protected $email;
    /**
     * @ORM\Column(type="string", unique=true, nullable = false)
     */
    protected $login;

    /**
     * @ORM\Column(type="string", length=60)
     */
    protected $password;

    /**
     * @ORM\Column(type="datetime" , nullable = false)
     */
    protected $created;
    /**
     * @ORM\Column(type="string", nullable = true)
     */
    protected $avatarPath;

    /**
     * @var mixed|null
     */
    private $repeat_password;


    /**
     * User constructor.
     * @param $name
     * @param $email
     * @param $login
     * @param $password
     * @param $hash
     * @param null $repeat_password
     * @param null $avatarPath
     */
    public function __construct($name, $email, $login, $password, $hash, $repeat_password = null, $avatarPath = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->login = $login;
        $this->password = $password;
        $this->created = new \DateTime("now");
        $this->avatarPath = $avatarPath;
        $this->hash = $hash;
        $this->repeat_password = $repeat_password;
    }


    /**
     * @return string|null
     */
    public function getRepeatPassword(): ?string
    {
        return $this->repeat_password;
    }


    /**
     * @param string|null $repeat_password
     */
    public function setRepeatPassword(?string $repeat_password): void
    {
        $this->repeat_password = $repeat_password;
    }

    /**
     * @return mixed
     */
    public function getAvatarPath()
    {
        return $this->avatarPath;
    }

    /**
     * @param mixed $avatarPath
     */
    public function setAvatarPath($avatarPath): void
    {
        $this->avatarPath = $avatarPath;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login): void
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @OneToMany (targetEntity="Comment", mappedBy="user", cascade={"all"})
     */
    protected $comments;

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @ORM\Column(type="datetime" , nullable = true)
     */
    protected $updated;
    /**
     * @ORM\Column(type="string" , nullable = true)
     */
    protected $hash;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


    /**
     * @param $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }


    public function rules()
    {
        return [
            "name" => [
                "asserts" => [
                    "lengthMax" => "param:45|message:User name is too long",
                    "lengthMin" => "param:4|message:User name is too short",
                    "blank" => "message:User name must be not blank",
                ],
                "value" => $this->name
            ],
            "email" => [
                "asserts" => ["email" => "message:It is not email",
                    "blank" => "message:Email must be not blank",
                    "unique" => "param:email|message:This email is already registered"],
                "value" => $this->email
            ],
            "password" => [
                "asserts" => [

                    "lengthMin" => "param:4|message:Password is too short",
                    "lengthMax" => "param:65|message:Password is too long",
                    "blank" => "message:Password must be not blank"],

                "value" => $this->password
            ],
            "login" => [
                "asserts" => [
                    "unique" => "param:login|message:This login is already registered",
                    "blank" => "message:Login must be not blank"],
                "value" => $this->login
            ],
            "repeat_password" => [
                "asserts" => [
                    "equal" => "param:" . $this->repeat_password . "|message:Passwords must match",],
                "value" => $this->password
            ],
        ];
    }


    public function toJson()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "password" => $this->password,
            "created" => $this->created,
            "updated" => $this->updated,
            "hash" => $this->hash,
            "login" => $this->login,
            "email" => $this->email
        ];
    }

}