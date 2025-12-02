<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplatEngine;
use App\Config\Paths;

class HomeController
{
    private TemplatEngine $view;

    public function __construct()
    {
        $this->view = new TemplatEngine(Paths::VIEWS);
    }

    public function home()
    {
        echo $this->view->render("/index.php", ['title' =>'Home',]);
    }
}
