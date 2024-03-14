<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManyToManyRelationOnArticlesToArticlesTableTXoMKaUpJnjFZ7tP extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_article', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_id')->nullable();
            $table->unsignedBigInteger('related_article_id')->nullable();
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
