<?php


namespace Test\models;


use Doctrine\ORM\EntityManager;
use Test\helpers\AuthHelper;

class CommentModel extends Model
{
    /**
     * @var EntityManager
     */
    private $em;
    private $comment;

    /**
     * CommentModel constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, $id = null)
    {
        parent::__construct($em);
        $this->em = $em;
        $this->comment = $this->getById($id);
    }


    private function getById($id)
    {
        if ($id) {
            return $this->em->getRepository("Test\models\Comment")->findById($id);
        } else {
            return new Comment();
        }
    }

    public function create($request): Comment
    {
        $parsedBody = $request->getParsedBody();
        if (isset($parsedBody["file_id"])) {
            $file = $this->em
                ->getRepository("Test\models\File")
                ->find($parsedBody["file_id"]);
        } else {
            $file = null;
        }
        $commentRepository = $this->em->getRepository("Test\models\Comment");
        if (isset($parsedBody["parent_id"])) {
            $parent = $commentRepository->findById($parsedBody["parent_id"] ?? null)[0];

        } else {
            $parent = null;
        }
        $comment = new Comment(
            $parsedBody["comment"] ?? null,
            AuthHelper::getAuthUser(),
            $file,
            $parent
        );
        $this->em->persist($comment);
        $this->em->flush();
        return $comment;
    }


    public function getByDate($request, $comment)
    {
        $parsedBody = $request->getParsedBody();
        $commentRepository = $this->em->getRepository("Test\models\Comment");

        if (isset($parsedBody["parent_id"])) {
            $newComment = $commentRepository->getNewCommentsChild($parsedBody["parent_id"], $comment->getCreated());
        } else {
            $newComment = $commentRepository->getNewFileComments($parsedBody["file_id"], $comment->getCreated());
        }
        return $newComment;
    }

    public function countChildren($request): ?int
    {
        $repository = $this->em->getRepository("Test\models\Comment");
        $count = $repository->countCommentsChildren($request->getAttribute("id"));
        return $count;
    }

    public function getChildren($request)
    {
        $comments = $this->em
            ->getRepository("Test\models\Comment")
            ->getCommentsChildren(
                $request->getAttribute("id"),
                (int)$request->getQueryParams()["amount"]
            );

        return $comments;
    }

    public function getComments($request)
    {
        $comments = $this->em
            ->getRepository("Test\models\Comment")
            ->getCommentsByFile($request->getAttribute("id"), (int)$request->getQueryParams()["amount"]);
        return $comments;
    }
}