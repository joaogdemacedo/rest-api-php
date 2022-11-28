<?php


namespace App\Controllers;


use App\Plugins\Di\Injectable;
use App\Plugins\Http\Response\Ok;
use App\Services\AuthenticationService;
use App\Utils\Utils;

class AuthenticationController extends Injectable
{
    private AuthenticationService $authenticationService;

    public function __construct(){
        $dbConnection = Utils::getDbConnection();
        $this->authenticationService = new AuthenticationService($dbConnection);
    }


    public function authentication()
    {
        $token = $this->authenticationService->authenticate(Utils::getDecodeJson());
        return (new Ok(['token' => $token]))->send();
    }

}