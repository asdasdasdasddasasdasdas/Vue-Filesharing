<?php


namespace Test\models;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity
 * @ORM\Table(name="files")
 * @ORM\Entity(repositoryClass="Test\Repositories\FileRepository")
 */
class File
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $name;
    /**
     * @ORM\Column(type="integer" )
     */
    protected $size;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;
    /**
     * @ORM\Column(type="string")
     */
    protected $filepath;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;


    /**
     * File constructor.
     * @param $name
     * @param $size
     * @param $type
     * @param $filepath
     * @param $created
     * @param $user
     */
    public function __construct($name, $size, $type, $filepath, $user = null)
    {
        $this->name = $name;
        $this->size = $size;
        $this->type = $type;
        $this->filepath = $filepath;
        $this->created = new \DateTime("now");
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @ManyToOne(targetEntity="User", cascade={"all"})
     */
    protected $user;

    /**
     * @OneToMany(targetEntity="Comment", mappedBy="file", cascade={"all"})
     */
    protected $comments;

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }


    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user->toArray();
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }


    public function setCreated()
    {
        $this->created = (new \DateTime("now"));

    }

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
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size): void
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * @param mixed $filepath
     */
    public function setFilepath($filepath): void
    {
        $this->filepath = $filepath;
    }

    public function rules()
    {
        return [
            "filename" => [
                "asserts" => ["lengthMax" => "param:255|message:File name is too big", "blank" => "message:File name must be not blank",],
                "value" => $this->name],
            "size" => [
                "asserts" => [
                    "max" => "param:" . (1024 * 1024 * 1024 * 2) . "|message:This file is too big"],
                "value" => $this->size]
        ];
    }
}