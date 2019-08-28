<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePodcastEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podcast_episodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('podcast_id');
            $table->string('guid');
            $table->string('title');
            $table->string('image_url')->nullable();
            $table->string('file_url');
            $table->integer('number')->nullable();
            $table->integer('season')->nullable();
            $table->string('episode_type');
            $table->timestamp('published_at')->nullable();
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
        Schema::dropIfExists('podcast_episodes');
    }
}
