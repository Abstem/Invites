<?php

namespace Abstem\Invites\Classes;

use Abstem\Invites\Contracts\GeneratorContract;
use Carbon\Carbon;
use Abstem\Invites\Models\Invite;
use Illuminate\Support\Str;

class Generator implements GeneratorContract
{
    protected $amount = 1;
    protected $uses = 1;
    protected $expiry;
    protected $manager;

    public function __construct(InvitesManager $manager)
    {
        $this->manager = $manager;
    }


    /**
     * @param int $amount
     * @return $this
     */
    public function times(int $amount = 1)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param int $amount
     * @return $this
     */
    public function uses(int $amount = 1)
    {
        $this->uses = $amount;

        return $this;
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    public function expiresOn(Carbon $date)
    {
        $this->expiry = $date;

        return $this;
    }

    /**
     * @param int $days
     * @return $this
     */
    public function expiresIn($days = 14)
    {
        $this->expiry = Carbon::now(config('app.timezone'))
            ->addDay($days)
            ->endOfDay();

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function make()
    {
        $invites = collect();

        for ($i = 0; $i < $this->amount; $i++) {
            $invite = $this->build();
            $invites->push($invite);
            $invite->save();
        }

        return $invites;
    }


    /**
     * @return Invite
     */
    protected function build(): Invite
    {
        $invite = new Invite;
        $invite->code = Str::upper($this->manager->code());
        $invite->max = $this->uses;
        $invite->valid_until = $this->expiry;

        return $invite;
    }
}