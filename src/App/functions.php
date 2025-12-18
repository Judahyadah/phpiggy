<?php

declare(strict_types=1);

function dd($value)
{
    echo "<pre>";
    print_r($value);
    echo "<pre>";
    die();
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value);
}

function redirectTo(string $path){
    header("location: {$path}");
    http_response_code(302);
    exit();
}