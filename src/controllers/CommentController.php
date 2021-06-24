<?php


namespace Test\controllers;


use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Test\models\CommentModel;

class CommentController
{
    /**
     * @var EntityManager
     */
    private $em;


    /**
     * CommentController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function postComment(RequestInterface $request, ResponseInterface $response)
    {
        $commentModel = new CommentModel($this->em);
        $createdComment = $commentModel->create($request);
        $newComment = $commentModel->getByDate($request, $createdComment);

        $response->getBody()->write(json_encode($newComment));
        return $response;
    }

    public function getComments(RequestInterface $request, ResponseInterface $response)
    {
        $commentModel = new CommentModel($this->em);
        $comments = $commentModel->getComments($request);
        $response->getBody()->write(json_encode($comments));
        return $response;
    }

    public function getChildComments(RequestInterface $request, ResponseInterface $response)
    {
        $commentModel = new CommentModel($this->em);
        $comments = $commentModel->getChildren($request);
        $response->getBody()->write(json_encode($comments));
        return $response;
    }

    public function countChildComments(RequestInterface $request, ResponseInterface $response)
    {
        $commentModel = new CommentModel($this->em);
        $count = $commentModel->countChildren($request);
        $response->getBody()->write(json_encode($count));
        return $response;
    }
}