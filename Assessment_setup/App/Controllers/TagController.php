<?php

namespace App\Controllers;

use App\Plugins\Http\Response\Created;
use App\Services\AuthenticationService;
use App\Services\TagService;
use App\Utils\Utils;

class TagController extends AuthenticationController
{
    private TagService $tagService;
    private AuthenticationService $authenticationService;

    public function __construct()
    {
        $dbConnection = Utils::getDbConnection();
        $this->tagService = new TagService($dbConnection);
        $this->authenticationService = new AuthenticationService($dbConnection);
    }


    public function createTag()
    {
        $employeeId = $this->authenticationService->validateToken();

        $tag = $this->tagService->createTag(Utils::getDecodeJson());
        return (new Created($tag))->send();
    }


    public function listTags()
    {
        $employeeId = $this->authenticationService->validateToken();

        $tags = $this->tagService->listTags();
        return (new Created($tags))->send();
    }

}