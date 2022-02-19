<?php

namespace Ketyl\Vine\Database;

use PDO;

class DB
{
    public ?PDO $pdo = null;

    public function __construct(
        protected string $driver = 'sqlite',
        protected string $hostname = 'localhost',
        protected string $database = ':memory:',
        protected string $user = 'vine',
        protected string $password = 'vine'
    ) {
        $this->driver = $driver;
        $this->hostname = $hostname;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;

        $this->establishConnection();
    }

    public function select(string $query, array $options = [])
    {
        return $this->pdo->query($query, PDO::FETCH_OBJ)->fetchAll();
    }

    private function establishConnection()
    {
        $this->pdo = new PDO(
            sprintf('%s:dbname=%s;host=%s', $this->driver, $this->database, $this->hostname),
            $this->user,
            $this->password
        );
    }
}
