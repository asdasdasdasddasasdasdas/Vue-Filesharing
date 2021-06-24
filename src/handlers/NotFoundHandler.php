<?php


namespace Test\handlers;


use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\CallableResolverInterface;
use Throwable;

class NotFoundHandler extends \Slim\Handlers\ErrorHandler
{
    protected $view;

    public function __construct(
        $view,
        CallableResolverInterface $callableResolver,
        ResponseFactoryInterface $responseFactory,
        ?LoggerInterface $logger = null) {
        parent::__construct($callableResolver, $responseFactory, $logger);
       $this->view =$view;
    }

    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;
        $errorCode = $exception->getCode();
        $response = $this->responseFactory->createResponse($errorCode);

        return $this->view->render($response,"404.twig", ["error"=>$errorCode]);
    }

}