<?php

declare(strict_types=1);

function dd($value)
{
    echo "<pre>";
    print_r($value);
    echo "<pre>";
    die();
}