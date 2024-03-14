<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteTestsBUfmwY51nTFuL6rZ extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tests');
        if (true) {
            // Workflow
            Schema::dropIfExists('test_workflows');            
            // Workflow - UserProfile
            Schema::dropIfExists('test_workflow_user_profile');
            // State
            Schema::dropIfExists('test_states');
            // Log
            Schema::dropIfExists('test_logs');
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
