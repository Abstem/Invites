<?php

namespace Abstem\Invites\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Invite extends Model
{
    protected $dates = ['valid_until'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('invites.invite_table_name');
        parent::__construct($attributes);
    }

    public function hasExpired()
    {
        if (is_null($this->valid_until)) {
            return false;
        }

        return $this->valid_until->isPast();
    }

    public function isFull()
    {
        if ($this->max == 0) {
            return false;
        }

        return $this->uses >= $this->max;
    }

    public function isUseless()
    {
        return $this->hasExpired() || $this->isFull();
    }

    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<', Carbon::now(config('app.timezone')));
    }

    public function scopeFull($query)
    {
        return $query->where('max', '!=', 0)->whereRaw('uses = max');
    }

    public function scopeUseless($query)
    {
        return $query
            ->where(function ($q) {
                $this->scopeExpired($q);
            })
            ->orWhere(function ($q) {
                $this->scopeFull($q);
            });
    }
}
