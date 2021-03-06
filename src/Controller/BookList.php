<?php

namespace Snowdog\Academy\Controller;

use Snowdog\Academy\Model\BookManager;
use Snowdog\Academy\Model\UserManager;

class BookList
{
    private BookManager $bookManager;
    private UserManager $userManager;

    public function __construct(BookManager $bookManager, UserManager $userManager)
    {
        $this->bookManager = $bookManager;
        $this->userManager = $userManager;
    }

    public function index(): void
    {
        require __DIR__ . '/../view/index/books.phtml';
    }

    private function getBooks(): array
    {
        return $this->bookManager->getAvailableBooks();
    }
}
