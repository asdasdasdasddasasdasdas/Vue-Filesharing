<?php


namespace Test\controllers;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Test\validator\Validator;

class HomeController
{
    private $view;
    private $em;


    public function __construct(Twig $view,$em)
    {
        $this->view = $view;
        $this->em = $em;
    }

    public function home(RequestInterface $request, ResponseInterface $response)
    {

        return $this->view->render($response, "home.twig", []);
    }
}