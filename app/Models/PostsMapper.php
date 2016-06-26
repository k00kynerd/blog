<?php

namespace Models;

use Library\MVC\Exceptions\DatabaseException;
use Library\MVC\Model\BaseMapper;

class PostsMapper extends BaseMapper
{
    protected static $objectClass = 'Models\Post';
    protected static $tableName = 'posts';

    /**
     * @param $id
     * @return mixed|null
     */
    public function findById($id)
    {
        $result = $this->adapter->select(
            'SELECT posts.*, users.name as user_name 
             FROM blog.posts 
             LEFT JOIN blog.users ON posts.user_id = users.id 
             WHERE posts.id=' . (int)$id . ' AND posts.is_deleted = 0 LIMIT 1'
        );
        if (0 === count($result)) {
            return null;
        }
        $row = current($result);

        return $this->mapObject($row);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $resultSet = $this->adapter->select(
            'SELECT posts.*, users.name as user_name 
             FROM blog.posts 
             LEFT JOIN blog.users ON posts.user_id = users.id 
             WHERE posts.is_deleted = 0 ORDER BY posts.created_at DESC 
             LIMIT ' . $this->limit . ' OFFSET ' . $this->offset
        );
        $entries = [];

        foreach ($resultSet as $row) {
            $entries[] = $this->mapObject($row);
        }

        return $entries;
    }

    /**
     * @param Post $post
     * @return mixed|null
     * @throws DatabaseException
     */
    public function save(Post $post)
    {
        $mapping = [
            'user_id' => $post->getUserId(),
            'title' => ($post->getTitle() !== null) ? $this->adapter->quote($post->getTitle()) : 'NULL',
            'body' => $this->adapter->quote($post->getBody()),
            'created_at' => ($post->getCreatedAt() !== null) ? "'{$post->getCreatedAt()}'" : 'NULL',
            'is_deleted' => $post->isDeleted()
        ];

        $sqlQuery = 'INSERT INTO blog.posts (' . implode(', ', array_keys($mapping)) . ') 
                  VALUES (' . implode(', ', array_values($mapping)) . ')';

        $res = $this->adapter->query($sqlQuery);

        if (!$res) {
            throw new DatabaseException('Can\'t save comment object');
        }

        return $this->findById(
            $this->adapter->getLastId()
        );

    }
}