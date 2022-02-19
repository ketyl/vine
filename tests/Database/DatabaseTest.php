<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\Database\DB;

class DatabaseTest extends TestCase
{
    /** @test */
    public function can_create_database_connection()
    {
        $db = new DB(driver: 'sqlite', database: ':memory:');

        $db->pdo->exec('DROP TABLE IF EXISTS users');
        $db->pdo->exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL)');
        $db->pdo->exec('INSERT INTO users (name) VALUES ("Zak")');

        dd($db->select('SELECT * FROM users'));
    }
}
