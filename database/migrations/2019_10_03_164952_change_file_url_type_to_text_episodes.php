<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFileUrlTypeToTextEpisodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->text('file_url')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->string('file_url')->change();
        });
    }
}
