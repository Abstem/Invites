<?php

namespace Abstem\Invites\Classes;

use Abstem\Invites\Drivers\BasicDriver;
use Abstem\Invites\Drivers\UuidDriver;
use Illuminate\Foundation\Application;
use Illuminate\Support\Manager;

class InvitesManager extends Manager
{

    public function __construct(Application $application)
    {
        parent::__construct($application);
    }


    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['invites.driver'];
    }

    public function createBasicDriver()
    {
        return new BasicDriver;
    }

    public function createUuidDriver()
    {
        return new UuidDriver;
    }
}