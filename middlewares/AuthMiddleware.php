<?php 

namespace Y2thek\PhpMvcframeworkCore\middlewares;

use Y2thek\PhpMvcframeworkCore\Application;
use Y2thek\PhpMvcframeworkCore\middlewares\BaseMiddleware;
use Y2thek\PhpMvcframeworkCore\exception\ForBiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }
    public function execute()
    {
        if(Application::isGuest()){
            if(empty($this->actions) || in_array(Application::$app->controller->action,$this->actions)){
                throw new ForBiddenException();
            }
        }
    }
}

