<?php

namespace Abstem\Invites\Contracts;

interface InvitesContract
{
    /**
     * @param $code
     * @return null
     */
    public function redeem($code);

    /**
     * @param $code
     * @return boolean
     */
    public function check($code);

    /**
     * @return mixed
     */
    public function generate();
}