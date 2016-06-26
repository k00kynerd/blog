<?php

namespace Library\Database;

interface ConnectionInterface
{
    public function __construct(DbConf $conf);

    public function connect();

    public function query($query);
}