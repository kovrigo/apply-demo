<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateViewChatMessages2bpDzPSl6tm2iRQ9 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {      
        DB::statement($this->deleteView('chat_messages'));        
        DB::statement($this->createView('chat_messages', true));
        if (true) {
            // Workflow
            DB::statement($this->deleteView('chat_message_workflows'));
            DB::statement($this->createView('chat_message_workflows', true));
            // Workflow - UserProfile
            DB::statement($this->deleteView('chat_message_workflow_user_profile'));
            DB::statement($this->createView('chat_message_workflow_user_profile', false));
            // State
            DB::statement($this->deleteView('chat_message_states'));
            DB::statement($this->createView('chat_message_states', true));
            // Log
            DB::statement($this->deleteView('chat_message_logs'));
            DB::statement($this->createView('chat_message_logs', true));
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
