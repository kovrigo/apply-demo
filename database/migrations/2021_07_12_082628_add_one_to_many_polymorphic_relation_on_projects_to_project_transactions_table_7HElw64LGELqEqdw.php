<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOneToManyPolymorphicRelationOnProjectsToProjectTransactionsTable7HElw64LGELqEqdw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable(); 
            $table->string('owner_type')->nullable();
        });
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
