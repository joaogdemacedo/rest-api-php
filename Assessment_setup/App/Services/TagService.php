<?php

namespace App\Services;

use App\Entities\Tag;
use App\Exceptions\TagAlreadyExistsException;
use App\Exceptions\TagNotFoundException;
use App\Plugins\Db\Connection\Mysql;
use App\Plugins\Db\Db;
use App\Plugins\Http\Exceptions\Conflict;
use App\Plugins\Http\Exceptions\NotFound;
use App\Repositories\TagRepository;

class TagService
{
    private TagRepository $tagRepository;
    private Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
        $this->tagRepository = new TagRepository($this->db);
    }

    public function createTag(array $requestPayload): Tag
    {
        $tagName = $requestPayload['name'];

        try {
            $tag = $this->tagRepository->createTag($tagName);
        } catch (TagAlreadyExistsException $exception){
            throw new Conflict(['message' => 'Tag already exists.']);
        }
        return $tag;
    }

    public function listTags(): array
    {
        try {
            $tags = $this->tagRepository->listTags();
        } catch (TagNotFoundException $exception){
            throw new NotFound(['message' => 'Unable to find Tags.']);
        }
        return $tags;
    }


}