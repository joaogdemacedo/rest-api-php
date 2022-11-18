<?php

namespace App\Services;

use App\Exceptions\TagAlreadyExistsException;
use App\Exceptions\TagNotFoundException;
use App\Plugins\Http\Exceptions\Conflict;
use App\Plugins\Http\Exceptions\NotFound;
use App\Repositories\TagRepository;

class tagService
{
    private TagRepository $tagRepository;

    public function __construct()
    {
        $this->tagRepository = new TagRepository();
    }

    public function createTag(array $requestPayload)
    {
        $tagName = $requestPayload['name'];

        try {
            $tag = $this->tagRepository->createTag($tagName);
        } catch (TagAlreadyExistsException $exception){
            throw new Conflict(['message' => 'Tag already exists.']);
        }
        return $tag;
    }

    public function listTags()
    {
        try {
            $tags = $this->tagRepository->listTags();
        } catch (TagNotFoundException $exception){
            throw new NotFound(['message' => 'Unable to find Tags.']);
        }
        return $tags;
    }


}