<?php

namespace Abstem\Invites\Contracts;

interface DriverContract
{
    /**
     * @return string
     */
    public function code(): string;
}