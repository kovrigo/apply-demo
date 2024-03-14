<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCities2rGDmq8VQ8xmwa5I extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('resource_title')->nullable()->default(null);
            // Tenant
            if (false) {
                $table->unsignedBigInteger('tenant_id')->nullable();            
                $table->foreign('tenant_id')
                    ->references('id')
                    ->on('tenants')
                    ->onDelete('cascade');
            }            
            // Owner
            if (false) {
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
            if (false) {
                $table->integer('sort_order')->nullable();
            }
            // Workflowable
            if (false) {
                // Workflow
                $table->unsignedBigInteger('city_workflow_id')->nullable();
                // State
                $table->unsignedBigInteger('city_state_id')->nullable();
            }
            $table->softDeletes();
            $table->timestamps();
        });

        if (false) {
            // Workflow
            Schema::create('city_workflows', function (Blueprint $table) {
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
            Schema::create('city_workflow_user_profile', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('sort_order')->nullable();
                $table->unsignedBigInteger('city_workflow_id')->nullable();
                $table->unsignedBigInteger('user_profile_id')->nullable();
                $table->timestamps();
            });
            // State
            Schema::create('city_states', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->json('name')->nullable();
                $table->text('code')->nullable();
                $table->unsignedBigInteger('tenant_id')->nullable();
                $table->foreign('tenant_id')
                    ->references('id')
                    ->on('tenants')
                    ->onDelete('cascade');                 
                $table->softDeletes();
                $table->timestamps();           
            });
            // Log
            Schema::create('city_logs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('city_state_id')->nullable();
                $table->unsignedBigInteger('city_id')->nullable();
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
