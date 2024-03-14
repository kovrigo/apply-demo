<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateViewCompanySizesN06A7D3Q3fSv9gpt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {      
        DB::statement($this->deleteView('company_sizes'));        
        DB::statement($this->createView('company_sizes', false));
        if (false) {
            // Workflow
            DB::statement($this->deleteView('company_size_workflows'));
            DB::statement($this->createView('company_size_workflows', true));
            // Workflow - UserProfile
            DB::statement($this->deleteView('company_size_workflow_user_profile'));
            DB::statement($this->createView('company_size_workflow_user_profile', false));
            // State
            DB::statement($this->deleteView('company_size_states'));
            DB::statement($this->createView('company_size_states', true));
            // Log
            DB::statement($this->deleteView('company_size_logs'));
            DB::statement($this->createView('company_size_logs', true));
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
