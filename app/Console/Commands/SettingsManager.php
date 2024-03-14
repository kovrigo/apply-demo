<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SettingsManager extends Command
{

    protected $meta;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:manage {action?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage resource settings';

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

        $actions = ['Restore', 'Backup', 'Staging', 'Production', 'Exit'];
        $selectedAction = $this->argument('action');
        if (is_null($selectedAction) || !in_array($selectedAction, $actions)) {
            $selectedAction = $this->choice('Choose action:', $actions, count($actions) - 1);
        }        
        switch ($selectedAction) {
            case 'Restore':
                $s = \App\ResourceSetting::where('name', 'resources')->first();
                $s->json_value = json_decode(file_get_contents(base_path() . '/backups/settings/resources.json'));
                $s->save();
                $s = \App\ResourceSetting::where('name', 'host_resources')->first();
                $s->json_value = json_decode(file_get_contents(base_path() . '/backups/settings/host_resources.json'));
                $s->save();
                $this->info('Resource settings have been restored from backup');
                break;
            case 'Backup':
                $json = json_encode(\App\ResourceSetting::where('name', 'resources')->first()->json_value);
                file_put_contents(base_path() . '/backups/settings/resources.json', $json);
                file_put_contents(base_path() . '/backups/settings/all/' . date('Y_m_d_His') . '_resources.json', $json);
                $json = json_encode(\App\ResourceSetting::where('name', 'host_resources')->first()->json_value);
                file_put_contents(base_path() . '/backups/settings/host_resources.json', $json);
                file_put_contents(base_path() . '/backups/settings/all/' . date('Y_m_d_His') . '_host_resources.json', $json);
                $this->info('Resource settings have been saved to the backups/settings folder in the project root');
                break;
            case 'Staging':
                $this->sync('mysql', 'staging_mysql');
                $this->info('Resource settings have been sent to the staging server');
                break;
            case 'Production':
                $this->sync('mysql', 'production_mysql');
                $this->info('Resource settings have been sent to the production server');
                break;                
            default:
                break;
        }
    }

    public function sync($connectionFrom, $connectionTo)
    {
        $settingsFrom = DB::connection($connectionFrom)
            ->table('resource_settings')
            ->whereNotNull('name')
            ->get();
        foreach ($settingsFrom as $settingFrom) {
            // Prepare data to sync
            $data = [
                'name' => $settingFrom->name,
                'is_json' => $settingFrom->is_json,
                'json_value' => $settingFrom->json_value,
                'js_value' => $settingFrom->js_value,
                'deleted_at' => $settingFrom->deleted_at,
            ];
            // Sync data
            DB::connection($connectionTo)
                ->table('resource_settings')
                ->updateOrInsert(['name' => $settingFrom->name],  $data);
        }
    }

}
