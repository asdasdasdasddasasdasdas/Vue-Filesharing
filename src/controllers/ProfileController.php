<?php


namespace Test\controllers;


use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Test\helpers\AuthHelper;
use Test\models\User;

class ProfileController
{
    private $view;
    private $em;

    /**
     * ProfileController constructor.
     */
    public function __construct($view, EntityManager $em)
    {
        $this->view = $view;
        $this->em = $em;
    }


    public function profileView(Request $request, $response)
    {

        $user = $this->em->getRepository("Test\models\User")->findById($request->getAttribute("id"));

        if (!$user) {
            throw new HttpNotFoundException($request);
        };
        return $this->view->render($response, "profileView.twig");
    }

    public function profile(Request $request, $response)
    {
        return $this->view->render($response, "profile.twig", ["csrf" => $request->getAttribute("csrf-token")
        ]);
    }

}