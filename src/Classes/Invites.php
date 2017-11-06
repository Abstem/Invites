<?php

namespace Abstem\Invites\Classes;

use Abstem\Invites\Contracts\InvitesContract;
use Abstem\Invites\Exceptions\InvitesException;
use Abstem\Invites\Exceptions\ExpiredInviteCode;
use Abstem\Invites\Exceptions\InvalidInviteCode;
use Abstem\Invites\Exceptions\MaxUsersReached;
use Abstem\Invites\Models\Invite;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class Invites implements InvitesContract
{
    public $error = '';


    /**
     * @param $code
     * @return null
     */
    public function redeem($code)
    {
        $invite = $this->prep($code);

        $invite->increment('uses');
    }

    /**
     * @param $code
     * @return boolean
     */
    public function check($code)
    {
        try {
            $this->prep($code);
            return true;
        } catch (InvitesException $exception) {
            $this->error = $exception->getMessage();
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function generate()
    {
        return app(Generator::class);
    }

    /**
     * @param $code
     * @return Invite
     */
    protected function prep($code)
    {
        $this->error = '';
        $invite = $this->lookupInvite($code);
        $this->validateInvite($invite);

        return $invite;
    }

    /**
     * @param $code
     * @return Invite
     * @throws InvalidInviteCode
     */
    protected function lookupInvite($code)
    {
        try {
            return Invite::where('code', '=', Str::upper($code))->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new InvalidInviteCode(trans('Invites::messages.invalid', [
                'code'  => $code
            ]));
        }
    }

    /**
     * @param Invite $invite
     * @throws ExpiredInviteCode
     * @throws MaxUsersReached
     */
    protected function validateInvite(Invite $invite)
    {
        if ($invite->isFull()) {
            throw new MaxUsersReached(trans('Invites::messages.maxed', [
                'code'  => $invite->code
            ]));
        }

        if ($invite->hasExpired()) {
            throw new ExpiredInviteCode(trans('Invites::messages.expired', [
                'code'  => $invite->code
            ]));
        }
    }
}