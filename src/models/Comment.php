<?php


namespace Test\models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;


/**
 * @ORM\Entity
 * @ORM\Table(name="comments")
 *
 * @ORM\Entity(repositoryClass="Test\Repositories\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    /**
     * @ORM\Column(type="text")
     */
    protected $content;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;
    /**
     * @ManyToOne(targetEntity="User", cascade={"all"})
     */
    protected $user;
    /**
     * @ManyToOne(targetEntity="Comment", cascade={"all"})
     */
    protected $parent;
    /**
     * @OneToMany (targetEntity="Comment", cascade={"all"}, mappedBy="parent")
     */
    protected $child;

    /**
     * @return mixed
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @param mixed $child
     */
    public function setChild($child): void
    {
        $this->child = $child;
    }
    /**
     * @ManyToOne(targetEntity="File", cascade={"all"})
     */
    protected $file;
    /**
     * Comment constructor.
     * @param $content
     * @param $created
     * @param $updated
     * @param $user
     * @param $file
     */
    public function __construct($content = null, $user = null, $file = null, $parent = null)
    {
        $this->content = $content;
        $this->created = new \DateTime('now');
        $this->user = $user;
        $this->file = $file;
        $this->parent = $parent;
    }

    /**
     * @return mixed|null
     */
    public function getParent(): ?mixed
    {
        return $this->parent;
    }

    /**
     * @param mixed|null $parent
     */
    public function setParent(?mixed $parent): void
    {
        $this->parent = $parent;
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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }


    public function setCreated($created): void
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
    public function setUpdated(): void
    {
        $this->updated = new \DateTime('now');
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

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
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }


}