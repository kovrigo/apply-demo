<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManyToManyRelationOnArtistsToRecordsTableRDQRGNwysldx0yu4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record_artist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('record_id')->nullable();
            $table->unsignedBigInteger('artist_id')->nullable();
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
