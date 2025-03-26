<?php 

namespace SeasonalContent\Core\Exceptions;

class BackupException extends \Exception
{
    public function __construct($message, $code = 0, \Throwable $previous) {
        // some code

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }
}