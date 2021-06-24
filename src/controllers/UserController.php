<?php


namespace Test\controllers;


use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;

use Test\helpers\AuthHelper;

use Test\models\UserModel;
use Test\validator\Validator;


class UserController
{
    private $em;
    private $validator;

    public function __construct(EntityManager $em, Validator $validator)
    {

        $this->em = $em;
        $this->validator = $validator;
    }

    public function getUser(RequestInterface $request, ResponseInterface $response)
    {
        $userModel = new UserModel($this->em, $request->getAttribute("id"));
        $user = $userModel->getViewUser($request)[0];
        $response->getBody()->write(json_encode($user));
        return $response;
    }

    public function getAuthUser(Request $request, $response)
    {
        $userModel = new UserModel($this->em, $request->getAttribute("id"));
        $user = $userModel->getAuthUser($request);
        $response->getBody()->write(json_encode($user));
        return $response;
    }

    public function create(RequestInterface $request, ResponseInterface $response)
    {
        if ($request->getHeaderLine('X-Requested-With') !== 'XMLHttpRequest') {

            return $response->withHeader("Location", "/");
        }
        $userModel = new UserModel($this->em, $request->getAttribute("id"));
        $userModel->create($request);
        $user = $userModel->getData();
        $this->validator->validate($user->rules());
        if (!$this->validator->hasErrors()) {

            $userModel->save();
            $response = AuthHelper::setAuth($response, $user->getHash());

            return $response;
        } else {

            $response->getBody()->write(json_encode(["errors" => $this->validator->getErrors()]));
            return $response;
        }
    }

    public function update(RequestInterface $request, ResponseInterface $response)
    {
        $parsed_body = $request->getParsedBody();
        $avatar = isset($request->getUploadedFiles()["avatar"]) ? $request->getUploadedFiles()["avatar"] : null;
        if ($avatar) $this->validator->validate([
            "avatar_path" => [
                "asserts" => [
                    "equal" => "param:image/jpeg|message: Avatar must be of jpeg format"],
                "value" => $avatar->getClientMediaType()]
        ]);
        $userModel = new UserModel($this->em, $request->getAttribute("id"));
        $userModel->update($request);
        $user = $userModel->getData();
        $this->validator->validate([
            "name" => $user->rules()["name"],
            "password" => $user->rules()["password"],
        ]);

        if (!$this->validator->hasErrors()) {
            if ($avatar) $avatar->moveTo($user->getAvatarPath());
            $userModel->save();
            return $response;
        } else {
            $response->getBody()->write(json_encode(["errors" => $this->validator->getErrors()]));
            return $response;
        }
    }
}