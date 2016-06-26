<?php

namespace Models;

use Library\MVC\Exceptions\DatabaseException;
use Library\MVC\Exceptions\ValidationException;
use Library\MVC\Model\BaseMapper;

class CommentsMapper extends BaseMapper
{
    protected static $objectClass = 'Models\Comment';
    protected static $tableName = 'comments';

    /**
     * @param $postId
     * @param $id
     * @return mixed|null
     */
    public function findPostCommentById($postId, $id)
    {
        $result = $this->adapter->select(
            'SELECT comments.*, users.name as user_name 
             FROM blog.comments 
             LEFT JOIN blog.users ON comments.user_id = users.id 
             LEFT JOIN blog.posts ON comments.post_id = posts.id
             WHERE comments.id=' . (int)$id . ' AND comments.post_id=' . (int)$postId . ' 
                   AND comments.is_deleted = 0 AND posts.is_deleted = 0'
        );
        if (0 === count($result)) {
            return null;
        }
        $row = current($result);

        return $this->mapObject($row);
    }

    /**
     * @param $postId
     * @return array
     */
    public function findAllPostComments($postId)
    {
        $resultSet = $this->adapter->select(
            'SELECT comments.*, users.name as user_name 
             FROM blog.comments 
             LEFT JOIN blog.users ON comments.user_id = users.id 
             LEFT JOIN blog.posts ON comments.post_id = posts.id
             WHERE comments.post_id=' . (int)$postId . ' AND comments.is_deleted = 0 
                   AND posts.is_deleted = 0 LIMIT ' . $this->limit . ' OFFSET ' . $this->offset
        );
        $entries = [];

        foreach ($resultSet as $row) {
            $entries[] = $this->mapObject($row);
        }

        return $entries;
    }

    /**
     * @param Comment $comment
     * @return mixed|null
     * @throws DatabaseException
     * @throws ValidationException
     */
    public function save(Comment $comment)
    {
        $postMapper = new PostsMapper();
        $post = $postMapper->findById($comment->getPostId());
        if ($post === null) {
            throw new ValidationException('Not exist post id');
        }
        $mapping = [
            'user_id' => ($comment->getUserId() !== null) ? $comment->getUserId() : 'NULL',
            'post_id' => $comment->getPostId(),
            'body' => "'{$comment->getBody()}'",
            'created_at' => ($comment->getCreatedAt() !== null) ? "'{$comment->getCreatedAt()}'" : 'NULL',
            'is_deleted' => $comment->isDeleted()
        ];

        $sqlQuery = 'INSERT INTO blog.comments (' . implode(', ', array_keys($mapping)) . ') 
                  VALUES (' . implode(', ', array_values($mapping)) . ')';

        $res = $this->adapter->query($sqlQuery);

        if (!$res) {
            throw new DatabaseException('Can\'t save comment object');
        }

        return $this->findPostCommentById(
            $comment->getPostId(),
            $this->adapter->getLastId()
        );
    }
}