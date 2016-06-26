<?php

namespace Controllers;

use Library\DependencyInjection\DIRegistry;
use Library\MVC\Controller\BaseController;
use Library\MVC\Exceptions\BadRequestException;
use Library\MVC\Exceptions\NotFoundException;
use Library\Session;
use Models\Post;
use Models\PostsMapper;

class PostsController extends BaseController
{
    /**
     * @return string
     * @throws NotFoundException
     */
    public function getList()
    {
        $limit = (int)$this->request->get('limit');
        $offset = (int)$this->request->get('offset');

        $mapper = new PostsMapper();
        $posts = $mapper->setLimit($limit)
            ->setOffset($offset)
            ->findAll();

        if (count($posts) === 0) {
            throw new NotFoundException('No posts');
        }

        return json_encode($posts);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundException
     */
    public function getObject($id)
    {
        $mapper = new PostsMapper();
        $post = $mapper->findById($id);
        if ($post === null) {
            throw new NotFoundException('Post not found');
        }
        return json_encode($post);
    }

    /**
     * @return string
     * @throws BadRequestException
     */
    public function create()
    {
        $data = $this->request->getBodyJson();
        /** @var Session $session */
        $session = DIRegistry::getDI()->get('session');
        if (!array_key_exists('title', $data)) {
            throw new BadRequestException('Empty post title');
        }
        if (!array_key_exists('body', $data)) {
            throw new BadRequestException('Empty post body');
        }
        try {
            $post = (new Post())
                ->setTitle($data['title'])
                ->setBody($data['body'])
                ->setUserId($session->get('userId', null));
            $mapper = new PostsMapper();
            $post = $mapper->save($post);
        } catch (\Exception $e) {
            throw new BadRequestException($e->getMessage());
        }
        http_response_code(201);
        return json_encode($post);
    }
}