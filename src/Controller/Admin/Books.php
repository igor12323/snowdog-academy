<?php

namespace Snowdog\Academy\Controller\Admin;

use Snowdog\Academy\Model\Book;
use Snowdog\Academy\Model\BookManager;
use Snowdog\Academy\Model\BorrowManager;

class Books extends AdminAbstract
{
    private BookManager $bookManager;
    private BorrowManager $borrowManager;
    private ?Book $book;

    public function __construct(BookManager $bookManager,BorrowManager $borrowManager)
    {
        parent::__construct();
        $this->bookManager = $bookManager;
        $this->borrowManager = $borrowManager;
        $this->book = null;
    }

    public function index(): void
    {
        require __DIR__ . '/../../view/admin/books/list.phtml';
    }

    public function newBook(): void
    {
        require __DIR__ . '/../../view/admin/books/edit.phtml';
    }

    public function newBookPost(): void
    {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];
        $forChild=$_POST['forChild'];

        if (empty($title) || empty($author) || empty($isbn)||empty($forChild)) {
            $_SESSION['flash'] = 'Missing data';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            return;
        }

        $this->bookManager->create($title, $author, $isbn,$forChild);

        $_SESSION['flash'] = "Book $title by $author saved!";
        header('Location: /admin');
        
    }

    public function edit(int $id): void
    {
        $book = $this->bookManager->getBookById($id);
        if ($book instanceof Book) {
            $this->book = $book;
            require __DIR__ . '/../../view/admin/books/edit.phtml';
        } else {
            header('HTTP/1.0 404 Not Found');
            require __DIR__ . '/../../view/errors/404.phtml';
        }
    }

    public function editPost(int $id): void
    {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];

        if (empty($title) || empty($author) || empty($isbn)) {
            $_SESSION['flash'] = 'Missing data';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            return;
        }

        $this->bookManager->update($id, $title, $author, $isbn);

        $_SESSION['flash'] = "Book $title by $author saved!";
        header('Location: /admin');
    }
    
    public function loadBooks() : void
    {
        require __DIR__ . '/../../view/admin/books/load.phtml';
    }

    public function loadBooksPost(): void
    {
        $max_size = 1024*1024;
        if (is_uploaded_file($_FILES['myfile']['tmp_name'])) 
        {
            if ($_FILES['myfile']['size'] > $max_size) 
            {
                $_SESSION['flash'] = 'The file is too large!';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                return;
            } 
            else 
            {
                move_uploaded_file($_FILES['myfile']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/'.$_FILES['myfile']['name']);
                if(($file=fopen($_SERVER['DOCUMENT_ROOT'].'/'.$_FILES['myfile']['name'],"r"))!==false)
                {
                    while (($data = fgetcsv($file, 1000,';')) !== FALSE) 
                    {	
                        if (empty($data[0]) || empty($data[1]) || empty([2])||count($data)!=3)
                        {
                            $_SESSION['flash'] ='Incorrect data!';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                            return;
                        }
                        $this->bookManager->create($data[0], $data[1], $data[2]);
                    }
                    fclose($file);
                }
                unlink($_SERVER['DOCUMENT_ROOT'].'/'.$_FILES['myfile']['name']);
                $_SESSION['flash'] = "Added books from file!";
                header('Location: /admin');
            }
        } 
        else 
        {
            $_SESSION['flash'] ='Missing data';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            return;
        }
    }
    private function getBooks(): array
    {
        return $this->bookManager->getAllBooks();
    }
    
}
