<?php

namespace Y2thek\PhpMvcframeworkCore\exception;


class NotFoundException extends \Exception
{
    protected $code = 404;

    protected $message = "Not Found !";
}