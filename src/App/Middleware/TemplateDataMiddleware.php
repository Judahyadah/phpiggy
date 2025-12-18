<?php

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\TemplatEngine;


class TemplateDataMiddleware implements MiddlewareInterface 
{
    public function __construct(private TemplatEngine $view)
    {
    }
    public function process(callable $next)
    {
        $this->view->addGlobal('title', 'Expense Tracking App');

        $next();
    }
}