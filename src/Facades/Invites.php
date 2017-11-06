<?php

namespace Abstem\Invites\Facades;

use Illuminate\Support\Facades\Facade;

class Invites extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'invites';
    }
}