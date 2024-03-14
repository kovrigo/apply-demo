<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteTestResourcesOYQDHFdLi6xC8bnj extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('test_resources');
        if (true) {
            // Workflow
            Schema::dropIfExists('test_resource_workflows');            
            // Workflow - UserProfile
            Schema::dropIfExists('test_resource_workflow_user_profile');
            // State
            Schema::dropIfExists('test_resource_states');
            // Log
            Schema::dropIfExists('test_resource_logs');
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
