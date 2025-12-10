<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

/**
 * Исключение в случае когда предоставленные данны�final � для аутентификации не подходят
 */
final class InvalidCredentialsException extends Exception
{

}