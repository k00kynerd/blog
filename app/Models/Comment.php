<?php

namespace Models;

use Library\MVC\Exceptions\ValidationException;
use Library\MVC\Model\BaseModel;

class Comment extends BaseModel
{
    const DEFAULT_NAME = 'anonymous';
    const MAX_COMMENT_LENGTH = 255;

    /** @var int */
    public $id;

    /** @var int */
    public $userId;

    /** @var string */
    public $userName = self::DEFAULT_NAME;

    /** @var int */
    public $postId;

    /** @var string */
    public $body;

    /** @var string */
    public $createdAt;

    /** @var int */
    protected $isDeleted = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        if ($userId !== null) {
            $this->userId = (int)$userId;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     * @return $this
     */
    public function setUserName($userName)
    {
        if ($userName === null) {
            $userName = self::DEFAULT_NAME;
        }
        $this->userName = $userName;

        return $this;
    }

    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @param int $postId
     * @return $this
     */
    public function setPostId($postId)
    {
        $this->postId = (int)$postId;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $body
     * @return $this
     * @throws ValidationException
     */
    public function setBody($body)
    {
        $body = filter_var($body, FILTER_SANITIZE_STRING);
        if (strlen($body) > self::MAX_COMMENT_LENGTH) {
            $body = substr($body, 0, self::MAX_COMMENT_LENGTH);
        }
        if (empty($body)) {
            throw new ValidationException('Wrong body value');
        }
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $createdAt = new \DateTime($createdAt);
        $this->createdAt = $createdAt->format(self::DATE_FORMAT);

        return $this;
    }

    /**
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param int $isDeleted
     * @return $this
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = (int)(bool)$isDeleted;
        return $this;
    }
}