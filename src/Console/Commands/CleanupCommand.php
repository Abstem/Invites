<?php

namespace Abstem\Invites\Console\Commands;


use Abstem\Invites\Models\Invite;
use Illuminate\Console\Command;

class CleanupCommand extends Command
{
    protected $signature = 'invites:cleanup';

    protected $description = 'Remove expired items from the database';

    public function handle()
    {
        $useless = Invite::useless()->count();
        Invite::useless()->delete();
        $this->info('Deleted ' . $useless . ' expired invites from database.');
    }
}