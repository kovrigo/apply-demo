<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Facades\DB;
use App\Tenant;
use Auth;

class Deploy extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        // Get env and select a pair of DB connections to sync data from and to
        $env = config('app.env');
        if ($env == 'production') {
            return;
        }
        $connectionFrom = 'mysql';
        $connectionTo = 'staging_mysql';        
        if ($env == 'staging') {
            $connectionFrom = 'staging_mysql';
            $connectionTo = 'production_mysql';
        }

        // Tenant
        $tenantIdFrom = Auth::user()->tenant_id;
        $tenantCodeFrom = Tenant::where('id', $tenantIdFrom)->first()->code;
        $tenantIdTo = DB::connection($connectionTo)
            ->table('tenants')
            ->where('code', $tenantCodeFrom)
            ->first()
            ->id;

        // Resource custom settings
        $settingsFrom = DB::connection($connectionFrom)
            ->table('resource_custom_settings')
            ->where('tenant_id', $tenantIdFrom)
            ->whereNotNull('name')
            ->get();
        foreach ($settingsFrom as $settingFrom) {
            // Prepare data to sync
            $data = [
                'tenant_id' => $tenantIdTo, 
                'name' => $settingFrom->name,
                'is_json' => $settingFrom->is_json,
                'json_value' => $settingFrom->json_value,
                'js_value' => $settingFrom->js_value,
                'deleted_at' => $settingFrom->deleted_at,
            ];
            // Sync data
            DB::connection($connectionTo)
                ->table('resource_custom_settings')
                ->updateOrInsert(['tenant_id' => $tenantIdTo, 'name' => $settingFrom->name],  $data);
        }

        // Roles
        $rolesFrom = DB::connection($connectionFrom)
            ->table('user_profiles')
            ->where('tenant_id', $tenantIdFrom)
            ->get();
        foreach ($rolesFrom as $roleFrom) {
            // Prepare data to sync
            $data = [
                'tenant_id' => $tenantIdTo, 
                'code' => $roleFrom->code,
                'name' => $roleFrom->name,
                'settings' => $roleFrom->settings,
                'deleted_at' => $roleFrom->deleted_at,
            ];
            // Sync data
            DB::connection($connectionTo)
                ->table('user_profiles')
                ->updateOrInsert(['tenant_id' => $tenantIdTo, 'code' => $roleFrom->code],  $data);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
