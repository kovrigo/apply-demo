<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateViewProjectTransactionsPOPCB40diJratQLP extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {      
        DB::statement($this->deleteView('project_transactions'));        
        DB::statement($this->createView('project_transactions', true));
        if (false) {
            // Workflow
            DB::statement($this->deleteView('project_transaction_workflows'));
            DB::statement($this->createView('project_transaction_workflows', true));
            // Workflow - UserProfile
            DB::statement($this->deleteView('project_transaction_workflow_user_profile'));
            DB::statement($this->createView('project_transaction_workflow_user_profile', false));
            // State
            DB::statement($this->deleteView('project_transaction_states'));
            DB::statement($this->createView('project_transaction_states', true));
            // Log
            DB::statement($this->deleteView('project_transaction_logs'));
            DB::statement($this->createView('project_transaction_logs', true));
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
