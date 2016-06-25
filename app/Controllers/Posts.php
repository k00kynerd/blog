<?php

namespace Controllers;

use Library\MVC\Controller\BaseController;

class Posts extends BaseController
{
    public function getList()
    {
        var_dump($this->app);
        return 'posts list';
    }

    public function getObject($id)
    {
        return 'Posts id ' . $id;
    }

    public function create()
    {
        return 'Post create';
    }
}