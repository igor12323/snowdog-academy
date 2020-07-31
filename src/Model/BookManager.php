<?php

namespace Snowdog\Academy\Model;

use Snowdog\Academy\Core\Database;

class BookManager
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function create(string $title, string $author, string $isbn, bool $is_for_child=false): int
    {
        $statement = $this->database->prepare('INSERT INTO books (title, author, isbn, is_for_child) VALUES (:title, :author, :isbn, :is_for_child)');
        $statement->bindParam(':title', $title, Database::PARAM_STR);
        $statement->bindParam(':author', $author, Database::PARAM_STR);
        $statement->bindParam(':isbn', $isbn, Database::PARAM_STR);
        $statement->bindParam(':is_for_child', $is_for_child, Database::PARAM_BOOL);
        $statement->execute();

        return (int) $this->database->lastInsertId();
    }

    public function update(int $id, string $title, string $author, string $isbn): void
    {
        $statement = $this->database->prepare('UPDATE books SET title = :title, author = :author, isbn = :isbn WHERE id = :id');
        $binds = [
            ':id' => $id,
            ':title' => $title,
            ':author' => $author,
            ':isbn' => $isbn
        ];

        $statement->execute($binds);
    }

    public function getBookById(int $id)
    {
        $query = $this->database->prepare('SELECT * FROM books WHERE id = :id');
        $query->setFetchMode(Database::FETCH_CLASS, Book::class);
        $query->execute([':id' => $id]);

        return $query->fetch(Database::FETCH_CLASS);
    }

    public function getAllBooks(): array
    {
        $query = $this->database->query('SELECT * FROM books');
        return $query->fetchAll(Database::FETCH_CLASS, Book::class);
    }

    public function getAvailableBooks(): array
    {
        if($_SESSION['is_child'])
        { 
           $query = $this->database->query('SELECT * FROM books WHERE borrowed = 0 and is_for_child=1');
        }
        else
        { 
           $query = $this->database->query('SELECT * FROM books WHERE borrowed = 0');
        }

        return $query->fetchAll(Database::FETCH_CLASS, Book::class);
    }
}
