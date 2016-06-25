<?php

namespace Controllers;

use Library\MVC\Controller\BaseController;

class Comments extends BaseController
{
    public function getList($postId)
    {
        return 'comments list' . $postId;
    }

    public function getObject($postId, $id)
    {
        return 'comments object ' . $id . ' from ' . $postId . ' post';
    }

    public function create($postId)
    {
        return 'comment create for ' . $postId;
    }
}