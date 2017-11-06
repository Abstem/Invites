<?php

namespace Abstem\Invites\Drivers;

use Abstem\Invites\Contracts\DriverContract;
use Illuminate\Support\Str;

class BasicDriver implements DriverContract
{

    /**
     * @return string
     */
    public function code(): string
    {
        return Str::random(config('invites.basic.length', 5));
    }
}