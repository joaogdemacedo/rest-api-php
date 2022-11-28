<?php

namespace App\Repositories;

use App\Entities\Tag;
use App\Exceptions\TagAlreadyExistsException;
use App\Exceptions\TagNotFoundException;
use App\Plugins\Db\Db;

class TagRepository
{
    private Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }


    public function getTagById(int $id): Tag
    {
        $sqlGetTag = '
            SELECT * FROM tag 
            WHERE tag.id = :tagId';

        $this->db->executeQuery($sqlGetTag,['tagId' => $id]);
        $tag = $this->db->getStatement()->fetch();
        if(empty($tag)){
            throw new TagNotFoundException();
        }
        return new Tag(
            $tag['id'],
            $tag['name']
        );
    }


    public function getTagByName(string $tagName): Tag
    {
        $sqlGetTag = '
            SELECT * FROM tag 
            WHERE tag.name = :tagName';

        $this->db->executeQuery($sqlGetTag,['tagName' => $tagName]);
        $tag = $this->db->getStatement()->fetch();
        if(empty($tag)){
            throw new TagNotFoundException();
        }
        return new Tag(
            $tag['id'],
            $tag['name']
        );
    }


    public function getTagsByFacilityId(int $facilityId): array
    {
        $sqlSelectTagNames = '
            SELECT t.id AS tag_id, t.name AS tag_name FROM facility AS f 
            INNER JOIN facility_tag AS ft ON ft.facility_id=f.id
            INNER JOIN tag AS t ON t.id = ft.tag_id
            WHERE f.id = :facilityId';

        $this->db->executeQuery($sqlSelectTagNames,['facilityId' => $facilityId]);
        $results = $this->db->getStatement()->fetchAll();
        $tags = [];
        foreach ($results as $result) {
            $tags[] = new Tag(
                intval($result['tag_id']),
                $result['tag_name']
            );
        }
        return $tags;
    }


    public function createTag(string $tagName): Tag
    {
        $sqlInsertTag = '
            INSERT INTO tag (name) 
            VALUES (:tagName);';

        $this->db->executeQuery($sqlInsertTag,['tagName' => $tagName]);
        $tagId = $this->db->getLastInsertedId();
        if ($tagId == 0){
            throw new TagAlreadyExistsException();
        }
        return new Tag(
            $tagId,
            $tagName
        );
    }


    public function listTags(): array
    {
        $sqlListTags = '
            SELECT * FROM tag
            ORDER BY id';

        $this->db->executeQuery($sqlListTags);
        $results = $this->db->getStatement()->fetchAll();
        if (empty($results)){
            throw new TagNotFoundException();
        }
        $tags = [];
        foreach ($results as $result){
            $tags[] = new Tag(
                $result['id'],
                $result['name'],
            );
        }
        return $tags;
    }



}