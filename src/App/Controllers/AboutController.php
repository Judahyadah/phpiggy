<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplatEngine;
use App\Config\Paths;

class AboutController
{
    public function __construct(private TemplatEngine $view)
    {
    }

    public function about()
    {
        echo $this->view->render("/about.php", [
            'title' =>'About',
            'dangerousData' => '<script>alert(123)</script>',
        ]);
    }
}
