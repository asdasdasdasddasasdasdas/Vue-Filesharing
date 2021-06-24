<?php


namespace Test\controllers;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Stream;
use Slim\Views\Twig;
use Test\models\FileModel;
use Test\validator\Validator;


class FileController
{
    private $view;
    private $validator;
    private $em;


    public function __construct(Twig $view, EntityManager $em, Validator $validator)
    {
        $this->view = $view;
        $this->validator = $validator;
        $this->em = $em;
    }

    public function file(Request $request, ResponseInterface $response)
    {
        $fileModel = new FileModel($this->em, $request->getAttribute("id"));
        if (!$fileModel->getData()) {
            throw new HttpNotFoundException($request);
        }


        return $this->view->render($response, "show_file.twig", ["csrf" => $request->getAttribute("csrf-token")]);
    }

    public function getFile(Request $request, ResponseInterface $response)
    {
        $fileModel = new FileModel($this->em, $request->getAttribute("id"));
        $file = $fileModel->getFileToJson($request);
        $response->getBody()->write(json_encode($file));
        return $response;
    }


    public function getFilesByQuery(Request $request, ResponseInterface $response)
    {


        $fileModel = new FileModel($this->em, $request->getAttribute("id"));
        $data = $fileModel->getByQuery($request);
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function download(RequestInterface $request, ResponseInterface $response)
    {


        $file = $this->em->getRepository("Test\models\File")->find($request->getAttribute("id"));

        return $response
            ->withBody(new Stream(fopen($file->getFilepath(), "rb")))
            ->withHeader("Cache-Control", "must-revalidate")
            ->withHeader("Expires", 0)
            ->withHeader("Pragma", "Public")
            ->withHeader("Cache-Control", "must-revalidate")
            ->withHeader("Content-Length", $file->getSize())
            ->withHeader("Content-Description", "File transfer")
            ->withHeader("Content-Type", $file->getType())
            ->withHeader("Content-Disposition", "attachment; filename={$file->getName()}");


    }

    public function uploadFiles(RequestInterface $request, \Slim\Psr7\Response $response)
    {


        if ($request->getHeaderLine('X-Requested-With') !== 'XMLHttpRequest') {
            return $response->withHeader("Location", "/");
        }
        $uplfiles = $request->getUploadedFiles()["files"];
        $this->validator->validate(["files" => ["asserts" => ["null" => "message:Files not found"], "value" => $uplfiles]]);

        $fileModel = new FileModel($this->em, 0);
        foreach ($uplfiles as $uplfile) {

            $file = $fileModel->map($uplfile);
            $this->validator->validate($file->rules());
        }

        if ($this->validator->hasErrors()) {
            $response->getBody()->write(json_encode(["errors" => $this->validator->getErrors()]));
            return $response;
        }

        foreach ($uplfiles as $uplfile) {

            $fileModel->create($uplfile);
            $user = $fileModel->getData();
        }
        $fileModel->save();

        return $response;
    }


}