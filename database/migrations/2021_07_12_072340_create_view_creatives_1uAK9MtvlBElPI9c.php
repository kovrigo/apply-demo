<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateViewCreatives1uAK9MtvlBElPI9c extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {      
        DB::statement($this->deleteView('creatives'));        
        DB::statement($this->createView('creatives', true));
        if (true) {
            // Workflow
            DB::statement($this->deleteView('creative_workflows'));
            DB::statement($this->createView('creative_workflows', true));
            // Workflow - UserProfile
            DB::statement($this->deleteView('creative_workflow_user_profile'));
            DB::statement($this->createView('creative_workflow_user_profile', false));
            // State
            DB::statement($this->deleteView('creative_states'));
            DB::statement($this->createView('creative_states', true));
            // Log
            DB::statement($this->deleteView('creative_logs'));
            DB::statement($this->createView('creative_logs', true));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }

    public function createView($table, $respectsTenancy)
    {
        if ($respectsTenancy) {
            return 'CREATE VIEW _' . $table . ' AS SELECT ' . $table . '.* from ' . $table . ' JOIN tenants ON ' . $table . '.tenant_id = tenants.id WHERE LOCATE(tenants.code, USER()) = 1';
        }
        return 'CREATE VIEW _' . $table . ' AS SELECT * from ' . $table;
    }

    public function deleteView($table)
    {
        return 'DROP VIEW IF EXISTS _' . $table;
    }

}
