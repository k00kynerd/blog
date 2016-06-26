<?php

namespace Models;

use Library\MVC\Exceptions\ValidationException;
use Library\MVC\Model\BaseModel;

class User extends BaseModel
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $email;

    /** @var string */
    protected $name;

    /** @var string */
    protected $passwordHash;

    /** @var string */
    protected $regDate;

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
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     * @throws ValidationException
     */
    public function setEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new ValidationException('Wrong email');
        }
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * @param string $passwordHash
     * @return $this
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegDate()
    {
        return $this->regDate;
    }

    /**
     * @param string $regDate
     * @return $this
     */
    public function setRegDate($regDate)
    {
        $regDate = new \DateTime($regDate);
        $this->regDate = $regDate->format(self::DATE_FORMAT);

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