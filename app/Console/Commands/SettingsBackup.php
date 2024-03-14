<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UserProfile;

class SettingsBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:backup {id} {--get}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup last settings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $env = config('app.env');
        if ($env != 'local') {
            $this->error('This command can only be run in local environment');
            exit;
        }
        $userProfileId = $this->argument('id');
        $isToGetSettings = $this->option('get');

        $userProfile = UserProfile::where('id', $userProfileId)->first();

        if ($isToGetSettings) {
            $userProfileSettings = $userProfile->settings_backup;
            $userProfile->settings = $userProfileSettings;
            $userProfile->saveWithoutEvents();
            return;
        }

        $userProfileSettings = $userProfile->settings;
        $userProfile->settings_backup = $userProfileSettings;
        $userProfile->saveWithoutEvents();
    }
}
