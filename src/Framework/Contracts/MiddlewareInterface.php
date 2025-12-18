<?php

namespace Framework\Contracts;

interface MiddlewareInterface 
{
    public function process(callable $next);
}