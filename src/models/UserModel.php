<?php


namespace Test\models;


use Doctrine\ORM\AbstractQuery;
use Slim\Psr7\Request;
use Test\helpers\Util;

class UserModel extends Model
{
    private $em;
    private $data;

    /**
     * UserModel constructor.
     * @param $em
     */
    public function __construct($em, $id)
    {
        parent::__construct($em);
        $this->em = $em;
        $this->data = $this->getById($id);
    }

    public function getById($id)
    {
        if ($id) {
            return $this->em->getRepository("Test\models\User")->find($id);
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function getAuthUser($request, $hydrate = AbstractQuery::HYDRATE_ARRAY)
    {
        return $this->em->getRepository("Test\models\User")->getUserByHash($request->getCookieParams()["hash"], $hydrate);

    }

    public function getViewUser(Request $request)
    {
        return $this->em->getRepository("Test\models\User")->getUserById($request->getAttribute("id"));
    }

    public function update(Request $request)
    {

        $user = $this->getAuthUser($request, AbstractQuery::HYDRATE_OBJECT)[0];

        $parsedBody = $request->getParsedBody();
        $name = $parsedBody["name"] ?? "";
        $password = $parsedBody["password"] ?? "";
        $avatar = $request->getUploadedFiles()["avatar"] ?? "";

        if (strlen($name) > 0) {
            $user->setName($name);
        }

        if (strlen($password > 0)) {
            $user->setPassword($password);
        }
        if ($avatar) {
            $slug = bin2hex(random_bytes(7));
            $avatarPath = "uploads/avatars/" . $slug . $avatar->getClientFilename();
            $user->setAvatarPath($avatarPath);
            if (!file_exists("uploads/avatars")) mkdir("uploads/avatars");
        }
        $this->data = $user;
        $this->em->persist($user);
    }

    public function create($request)
    {
        $parsedBody = $request->getParsedBody();
        $user = new User(
            $parsedBody["name"] ?? null,
            $parsedBody["email"] ?? null,
            $parsedBody["login"] ?? null,
            $parsedBody["password"] ?? null,
            Util::slug(15),
            $parsedBody["repeat_password"] ?? null
        );
        $this->data = $user;
        $this->em->persist($user);
    }


}