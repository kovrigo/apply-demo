<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsIs6skl9Z0GRyuhbv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('resource_title')->nullable()->default(null);
            // Tenant
            if (true) {
                $table->unsignedBigInteger('tenant_id')->nullable();            
                $table->foreign('tenant_id')
                    ->references('id')
                    ->on('tenants')
                    ->onDelete('cascade');
            }            
            // Owner
            if (true) {
                $table->unsignedBigInteger('user_id')->nullable();            
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users');                
            }
            // Translatable fields
            $fields = [];
            foreach ($fields as $field) {
                $table->json($field)->nullable();
            }
            // Sortable fields
            if (true) {
                $table->integer('sort_order')->nullable();
            }
            // Workflowable
            if (true) {
                // Workflow
                $table->unsignedBigInteger('project_workflow_id')->nullable();
                // State
                $table->unsignedBigInteger('project_state_id')->nullable();
            }
            $table->softDeletes();
            $table->timestamps();
        });

        if (true) {
            // Workflow
            Schema::create('project_workflows', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->text('settings')->nullable();                
                $table->unsignedBigInteger('tenant_id')->nullable();
                $table->foreign('tenant_id')
                    ->references('id')
                    ->on('tenants')
                    ->onDelete('cascade');
                $table->softDeletes();
                $table->timestamps();
            });
            // Workflow - UserProfile
            Schema::create('project_workflow_user_profile', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('sort_order');
                $table->unsignedBigInteger('project_workflow_id')->nullable();
                $table->unsignedBigInteger('user_profile_id')->nullable();
                $table->timestamps();
            });
            // State
            Schema::create('project_states', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->json('name')->nullable();
                $table->unsignedBigInteger('tenant_id')->nullable();
                $table->foreign('tenant_id')
                    ->references('id')
                    ->on('tenants')
                    ->onDelete('cascade');                 
                $table->softDeletes();
                $table->timestamps();           
            });
            // Log
            Schema::create('project_logs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('project_state_id')->nullable();
                $table->unsignedBigInteger('project_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();            
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
                $table->unsignedBigInteger('tenant_id')->nullable();            
                $table->foreign('tenant_id')
                    ->references('id')
                    ->on('tenants')
                    ->onDelete('cascade');
                $table->softDeletes();            
                $table->timestamps();
            });
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
}
