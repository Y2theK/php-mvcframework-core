<?php

namespace Y2thek\PhpMvcframeworkCore\exception;


class ForBiddenException extends \Exception
{
    protected $code = 403;

    protected $message = "You don't have permission to access this page !";
}