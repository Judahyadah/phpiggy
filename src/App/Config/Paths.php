<?php

declare(strict_types=1);

namespace App\Config;

class Paths{
    public const VIEWS = __DIR__ . '/../views/';
    // source here gos outside the 'App' folder
    public const  SOURCE = __DIR__ . '/../../';
    public const ROOT = __DIR__ .'/../../../';
}