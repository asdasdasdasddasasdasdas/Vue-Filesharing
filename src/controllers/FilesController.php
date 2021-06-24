<?php


namespace Test\controllers;


use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Views\Twig;
use Test\helpers\AuthHelper;
use Test\models\File;
use Test\models\User;
use Test\validator\Validator;

class FilesController
{
    protected $view;
    protected $em;
    private $validator;


    public function __construct(Twig $view, EntityManager $em)
    {
        $this->view = $view;
        $this->em = $em;

    }


    public function table(RequestInterface $request, ResponseInterface $response)
    {


        return $this->view->render($response, "filetable.twig", ["csrf" =>$request->getAttribute("csrf-token")]);
    }


    public function upload(RequestInterface $request, ResponseInterface $response)
    {

        return $this->view->render($response, "upload.twig", ["csrf" => $request->getAttribute("csrf-token")]);
    }


}