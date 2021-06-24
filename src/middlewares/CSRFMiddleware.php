<?php


namespace Test\middlewares;


use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Test\helpers\Util;

class CSRFMiddleware
{

    public function __invoke(Request $request, $handler)
    {
        $token = $request->getParsedBody()["csrf_token"] ?? false;
        $cookie_token = $request->getCookieParams()["csrf-token"] ?? false;

        if (!$cookie_token) {

            $token = Util::slug(15);
            $request = $request->withAttribute("csrf-token", $token);
            $response = $handler->handle($request);
            return  $response->withAddedHeader("Set-Cookie", "csrf-token=" . $token . "; Max-Age=" . (60 * 30) . "; Path=/");
        }
        $request = $request->withAttribute("csrf-token", $cookie_token);
        if ($request->getHeaderLine('X-Requested-With') == 'XMLHttpRequest') {

            if ($token == $cookie_token) {
                return $handler->handle($request);
            } else {

                $response = new Response();
                $response->withHeader("Content-Type", "application/json");
                $response->getBody()->write(json_encode(["errors"=>["csrf" => true]]));
                return $response;
            }
        }

        return $handler->handle($request);
    }
}