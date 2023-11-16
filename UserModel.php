<?php

namespace Y2thek\PhpMvcframeworkCore;

use Y2thek\PhpMvcframeworkCore\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName() : string;
}