<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteViewTestResourcesH3u3V4fF4xvNswoj extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        DB::statement($this->deleteView('test_resources'));
        if (true) {
            // Workflow
            DB::statement($this->deleteView('test_resource_workflows'));
            // Workflow - UserProfile
            DB::statement($this->deleteView('test_resource_workflow_user_profile'));
            // State
            DB::statement($this->deleteView('test_resource_states'));
            // Log
            DB::statement($this->deleteView('test_resource_logs'));
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

    public function deleteView($table)
    {
        return 'DROP VIEW IF EXISTS _' . $table;
    }
}
