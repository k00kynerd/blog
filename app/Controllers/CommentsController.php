<?php

namespace Controllers;

use Library\DependencyInjection\DIRegistry;
use Library\MVC\Controller\BaseController;
use Library\MVC\Exceptions\BadRequestException;
use Library\MVC\Exceptions\NotFoundException;
use Library\Session;
use Models\Comment;
use Models\CommentsMapper;

class CommentsController extends BaseController
{
    /**
     * @param int $postId
     * @return string
     * @throws NotFoundException
     */
    public function getList($postId)
    {
        $limit = (int)$this->request->get('limit');
        $offset = (int)$this->request->get('offset');

        $mapper = new CommentsMapper();
        $comments = $mapper->setLimit($limit)
            ->setOffset($offset)
            ->findAllPostComments($postId);

        if (count($comments) === 0) {
            throw new NotFoundException('No comments');
        }

        return json_encode($comments);
    }

    /**
     * @param int $postId
     * @param int $id
     * @return string
     * @throws NotFoundException
     */
    public function getObject($postId, $id)
    {
        $mapper = new CommentsMapper();
        $post = $mapper->findPostCommentById($postId, $id);
        if ($post === null) {
            throw new NotFoundException('Comment not found');
        }
        return json_encode($post);
    }

    /**
     * @param int $postId
     * @return string
     * @throws BadRequestException
     */
    public function create($postId)
    {
        $data = $this->request->getBodyJson();
        /** @var Session $session */
        $session = DIRegistry::getDI()->get('session');
        if (!array_key_exists('body', $data)) {
            throw new BadRequestException('Empty comment body');
        }
        try {
            $comment = (new Comment())
                ->setBody($data['body'])
                ->setUserId($session->get('userId', null))
                ->setPostId($postId);
            $mapper = new CommentsMapper();
            $comment = $mapper->save($comment);
        } catch (\Exception $e) {
            throw new BadRequestException($e->getMessage());
        }
        http_response_code(201);
        return json_encode($comment);
    }
}