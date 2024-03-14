<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateViewChatLogsLpXCUn8EkuPCiPxh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {      
        DB::statement($this->deleteView('chat_logs'));        
        DB::statement($this->createView('chat_logs', true));
        if (false) {
            // Workflow
            DB::statement($this->deleteView('chat_log_workflows'));
            DB::statement($this->createView('chat_log_workflows', true));
            // Workflow - UserProfile
            DB::statement($this->deleteView('chat_log_workflow_user_profile'));
            DB::statement($this->createView('chat_log_workflow_user_profile', false));
            // State
            DB::statement($this->deleteView('chat_log_states'));
            DB::statement($this->createView('chat_log_states', true));
            // Log
            DB::statement($this->deleteView('chat_log_logs'));
            DB::statement($this->createView('chat_log_logs', true));
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