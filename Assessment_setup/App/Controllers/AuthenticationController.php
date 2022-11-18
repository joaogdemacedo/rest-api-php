<?php


namespace App\Controllers;


use App\Plugins\Di\Injectable;
use App\Plugins\Http\Exceptions\NotFound;
use App\Plugins\Http\Exceptions\Unauthorized;
use App\Plugins\Http\Response\Ok;
use App\Services\AuthenticationService;

class AuthenticationController extends Injectable
{
    private AuthenticationService $authenticationService;

    public function __construct(){
        $this->authenticationService = new AuthenticationService();
    }


    public function authentication()
    {
        $getBody = file_get_contents('php://input');
        $decodeJson = json_decode($getBody, true);

        $token = $this->authenticationService->authenticate($decodeJson);
        return (new Ok(['token' => $token]))->send();
    }

}