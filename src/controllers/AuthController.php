<?php


namespace Test\controllers;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Test\helpers\AuthHelper;
use Test\models\User;
use Test\validator\Validator;

class AuthController
{

    /**
     * @var Twig
     */
    private $view;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var EntityManager
     */
    private $em;


    /**
     * AuthController constructor.
     * @param Twig $view
     * @param Validator $validator
     * @param EntityManager $em
     * @param AuthHelper $auth
     */
    public function __construct(Twig $view, Validator $validator, EntityManager $em)
    {
        $this->view = $view;
        $this->validator = $validator;
        $this->em = $em;

    }


    public function login(RequestInterface $request, ResponseInterface $response)
    {
        return $this->view->render($response, "login.twig", ["csrf" =>$request->getAttribute("csrf-token")]);
    }

    public function registration(RequestInterface $request, ResponseInterface $response)
    {


        return $this->view->render($response, "registration.twig", [
            "csrf" => $request->getAttribute("csrf-token")
        ]);
    }

    public function logout(RequestInterface $request, ResponseInterface $response)
    {

        $response = $response->withHeader("Set-Cookie", "hash=")->withHeader("location", "/");
        return $this->view->render($response, "registration.twig", [
            "csrf" => $request->getAttribute("csrf-token")
        ]);
    }


    public function loginXhr(RequestInterface $request, ResponseInterface $response)
    {

        if ($request->getHeaderLine('X-Requested-With') !== 'XMLHttpRequest') {
            return $response->withHeader("Location", "/");
        }

        $parsed_body = $request->getParsedBody();
        $this->validator->validate([
            "email" => [
                "asserts" => ["email" => "message: Email error", "Blank" => "message:Email must be not blank"],
                "value" => $parsed_body["email"] ?? null
            ],
            "password" => [
                "asserts" => ["lengthMin" => "param:7|message:Password is too short", "lengthMax" => "param:60|message:Password is too long","blank" => "message:Password must be not blank"],
                "value" => $parsed_body["password"] ?? null
            ]


        ]);

        if ($this->validator->hasErrors()) {
            $response->getBody()->write(json_encode([
                "errors" => $this->validator->getErrors()]));
            return $response;
        }


        return AuthHelper::login($parsed_body, $response);
    }
}