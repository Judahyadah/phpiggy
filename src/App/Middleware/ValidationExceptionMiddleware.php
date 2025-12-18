<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationExeption;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        try {
            $next();
        } catch (ValidationExeption $e) {
            $oldFormData = $_POST;

            $excludedFields = ['password', 'confirmPassword'];
            // the array_flip turns the string in the $excludedFields into key names then the array_diff_key is used to merge arrays and it also does not include any keys that are similar in the two arrays thereby removing all password related information from our $_POST field
            $formattedFormData = array_diff_key(
                $oldFormData, array_flip($excludedFields)
            );

            $_SESSION['errors'] = $e->errors;
            $_SESSION['oldFormData'] = $formattedFormData;

            $referer = $_SERVER['HTTP_REFERER'];
            redirectTo($referer);
        }
    }
}