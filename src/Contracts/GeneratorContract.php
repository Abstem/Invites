<?php

namespace Abstem\Invites\Contracts;

use Carbon\Carbon;

interface GeneratorContract
{
    /**
     * @param int $amount
     * @return $this
     */
    public function times(int $amount = 1);

    /**
     * @param int $amount
     * @return $this
     */
    public function uses(int $amount = 1);

    /**
     * @param Carbon $carbon
     * @return $this
     */
    public function expiresOn(Carbon $carbon);

    /**
     * @param int $days
     * @return mixed
     */
    public function expiresIn($days = 14);

    /**
     * @return \Illuminate\Support\Collection
     */
    public function make();
}