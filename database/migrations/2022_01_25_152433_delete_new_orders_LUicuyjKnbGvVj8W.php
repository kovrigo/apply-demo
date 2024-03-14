<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteNewOrdersLUicuyjKnbGvVj8W extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('new_orders');
        if (true) {
            // Workflow
            Schema::dropIfExists('new_order_workflows');            
            // Workflow - UserProfile
            Schema::dropIfExists('new_order_workflow_user_profile');
            // State
            Schema::dropIfExists('new_order_states');
            // Log
            Schema::dropIfExists('new_order_logs');
        }
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
