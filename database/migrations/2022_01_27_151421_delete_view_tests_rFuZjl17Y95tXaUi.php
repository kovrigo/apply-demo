<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteViewTestsRFuZjl17Y95tXaUi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        DB::statement($this->deleteView('tests'));
        if (true) {
            // Workflow
            DB::statement($this->deleteView('test_workflows'));
            // Workflow - UserProfile
            DB::statement($this->deleteView('test_workflow_user_profile'));
            // State
            DB::statement($this->deleteView('test_states'));
            // Log
            DB::statement($this->deleteView('test_logs'));
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
