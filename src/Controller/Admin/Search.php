<?php

namespace Snowdog\Academy\Controller\Admin;

use Snowdog\Academy\Model\NumberOfDays;
use Snowdog\Academy\Model\BorrowManager;

class Search extends AdminAbstract
{
    private ?NumberOfDays $numberOfDays;
    private BorrowManager $borrowManager;

    public function __construct(BorrowManager $borrowManager)
    {
        parent::__construct();
        $this->borrowManager = $borrowManager;
        $this->numberOfDays = null;
    }
    
    public function show(int $number_of_days): void
    {
        $this->numberOfDays=new NumberOfDays($number_of_days);
        require __DIR__ . '/../../view/admin/books/listDays.phtml';
    }
    public function showPost(): void
    {
        $this->numberOfDays=new NumberOfDays($_POST['number_of_days']);
        header('Location: /admin/show/'.$this->numberOfDays->getNumber());
    }
    private function getBooksByDays(int $number): array
    {
        return $this->borrowManager->getByDays($number);
    }
}
