<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplatEngine;
use App\Config\Paths;

class AboutController
{
    private TemplatEngine $view;

    public function __construct()
    {
        $this->view = new TemplatEngine(Paths::VIEWS);
    }

    public function about()
    {
        echo $this->view->render("/about.php", [
            'title' =>'About',
            'dangerousData' => '<script>alert(123)</script>',
        ]);
    }
}
