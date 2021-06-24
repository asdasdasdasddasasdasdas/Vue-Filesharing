<?php


namespace Test\models;


use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Http\Message\RequestInterface;
use Test\helpers\AuthHelper;
use Test\helpers\Util;

class FileModel extends Model
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var object|null
     */
    private $data;

    private $moveToFiles = [];

    /**
     * FileModel constructor.
     * @param EntityManager $em
     * @param $id
     */
    public function __construct(EntityManager $em, $id)
    {
        parent::__construct($em);
        $this->em = $em;
        $this->data = $this->getById($id);
    }

    public function getById($id)
    {
        if ($id) {
            return $this->em->getRepository("Test\models\File")->find($id);
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function getByQuery(RequestInterface $request)
    {


        $queryParams = $request->getQueryParams();

        $maxResult = 10;

        $page = $queryParams["page"] ?? 1;
        $page = (int)$page;
        $page = is_int($page) && $page > 0 ? $page : 1;

        $firstResult = ($page - 1) * $maxResult;

        if (isset($queryParams["search"])) {
            $search = "%" . $queryParams["search"] . "%";
        } else {
            $search = "%_%";
        }

        if (isset($queryParams["type"])) {
            $type = "%" . $queryParams["type"] . "%";
        } else {
            $type = "%_%";
        }
        $files = $this->em->getRepository("Test\models\File")->getByQuery($search, $type, $firstResult, $maxResult);
        $c = (new Paginator($files))->count();

        $data = [
            "files" => $files->getResult(AbstractQuery::HYDRATE_ARRAY),
            "pages" => ceil($c / 10)
        ];

        return $data;
    }

    public function getFileToJson($request)
    {
        return $this->em->getRepository("Test\models\File")->getById($request->getAttribute("id"))[0];
    }

    public function map($uplfile)
    {

        $slug = Util::slug(7);
        $filepath = "uploads/" . $slug . $uplfile->getClientFilename();
        if ($uplfile->getClientMediaType() == "application/octet-stream" ||
            $uplfile->getClientMediaType() == "text/html") {
            $filepath .= ".txt";
        }

        $file = new File(
            $uplfile->getClientFilename(),
            $uplfile->getSize(),
            $uplfile->getClientMediaType(),
            $filepath
        );
        return $file;
    }

    public function create($uplfile)
    {
        $user = AuthHelper::getAuthUser();
        if (!$user) {
            $user = $this->em->getRepository(User::class)->getAnonimUser()[0];
        }
        if (!$user) {
            $user = new User(
                "Anonim",
                "anonim@anonim.anonim",
                "anonim",
                "anonim",
                "anonim"
            );
            $this->em->persist($user);
            $this->em->flush();

        }
        $file = $this->map($uplfile);
        $file->setUser($user);
        $this->data = $file;
        $this->em->persist($file);

        $uplfile->moveTo($file->getFilepath());
    }
}