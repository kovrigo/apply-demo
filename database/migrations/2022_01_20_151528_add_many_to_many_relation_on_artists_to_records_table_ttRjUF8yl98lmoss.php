<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManyToManyRelationOnArtistsToRecordsTableTtRjUF8yl98lmoss extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artist_record', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('artist_id')->nullable();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->timestamps();
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
