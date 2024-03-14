<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteViewNewOrdersOBluaOgZYCyjmcy2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        DB::statement($this->deleteView('new_orders'));
        if (true) {
            // Workflow
            DB::statement($this->deleteView('new_order_workflows'));
            // Workflow - UserProfile
            DB::statement($this->deleteView('new_order_workflow_user_profile'));
            // State
            DB::statement($this->deleteView('new_order_states'));
            // Log
            DB::statement($this->deleteView('new_order_logs'));
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
