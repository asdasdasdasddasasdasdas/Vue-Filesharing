<?php


return function (\Slim\App $app) {
    $app->group("", function () use ($app) {


        $app->group("", function () use ($app) {
            $app->get('/upload', \Test\controllers\FilesController::class . ":upload")->setName("upload");
            $app->post('/upload/post', \Test\controllers\FileController::class . ":uploadFiles");
            $app->get('/login', \Test\controllers\AuthController::class . ":login")->setName("login");
            $app->get('/signup', \Test\controllers\AuthController::class . ":registration")->setName("signup");
            $app->post('/login/send', \Test\controllers\AuthController::class . ":loginXhr");
            $app->post('/signup/send', \Test\controllers\UserController::class . ":create");
            $app->get("/file/{id}", \Test\controllers\FileController::class . ":file");
            $app->post("/comment/post", \Test\controllers\CommentController::class . ":postComment");
            $app->post("/profile/update", \Test\controllers\UserController::class . ":update");
            $app->group("",function ()use($app){
                $app->get("/profile", \Test\controllers\ProfileController::class . ":profile");
            })->add(\Test\middlewares\AuthMiddleware::class);
        })->add(\Test\middlewares\CSRFMiddleware::class);
        $app->get('/logout', \Test\controllers\AuthController::class . ":logout")->setName("logout");
        $app->get('/', \Test\controllers\HomeController::class.":home")->setName("home");
        $app->get("/files", \Test\controllers\FilesController::class . ":table")->setName("files");
        $app->get("/files/search", \Test\controllers\FileController::class . ":getFilesByQuery")->setName("search");


        $app->get("/user/getAuthUser", \Test\controllers\UserController::class . ":getAuthUser");
        $app->get("/user/{id}", \Test\controllers\ProfileController::class . ":profileView");
        $app->get("/user/{id}/getUser", \Test\controllers\UserController::class . ":getUser");


        $app->get("/file/{id}/search", \Test\controllers\FileController::class . ":getFile");
        $app->get("/file/{id}/download", \Test\controllers\FileController::class . ":download")->setName("download");
        $app->get("/file/{id}/comments", \Test\controllers\CommentController::class . ":getComments");

        $app->get("/comment/{id}/childComments", \Test\controllers\CommentController::class . ":getChildComments");
        $app->get("/comment/{id}/count", \Test\controllers\CommentController::class . ":countChildComments");



    });
};