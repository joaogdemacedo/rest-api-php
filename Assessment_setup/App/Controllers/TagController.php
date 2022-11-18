<?php

namespace App\Controllers;

use App\Plugins\Http\Exceptions\BadRequest;
use App\Plugins\Http\Exceptions\Conflict;
use App\Plugins\Http\Exceptions\NotFound;
use App\Plugins\Http\Exceptions\Unauthorized;
use App\Plugins\Http\Response\Created;
use App\Services\AuthenticationService;
use App\Services\tagService;

class TagController extends AuthenticationController
{
    private TagService $tagService;
    private AuthenticationService $authenticationService;

    public function __construct()
    {
        $this->tagService = new TagService();
        $this->authenticationService = new AuthenticationService();
    }


    public function createTag()
    {
        $employeeId = $this->authenticationService->validateToken();

        $getBody = file_get_contents('php://input');
        $decodeJson = json_decode($getBody, true);

        $tag = $this->tagService->createTag($decodeJson);
        return (new Created($tag))->send();
    }


    public function listTags()
    {
        $employeeId = $this->authenticationService->validateToken();

        $tags = $this->tagService->listTags();
        return (new Created($tags))->send();
    }

}