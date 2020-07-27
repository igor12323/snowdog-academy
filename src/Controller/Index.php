<?php

namespace Snowdog\Academy\Controller;

class Index
{
    public function index(): void
    {
        if (isset($_SESSION['login'])&&$_SESSION['is_admin']) {
            header('Location: /admin');
            return;
        }

        if (isset($_SESSION['login'])) {
            header('Location: /books');
            return;
        }

        require __DIR__ . '/../view/index/index.phtml';
    }
}
