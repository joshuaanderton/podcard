<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveSubdomainToPodcasts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('podcasts', function (Blueprint $table) {
            $table->string('subdomain')->nullable()->unique();
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('subdomain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('podcasts', function (Blueprint $table) {
            $table->dropColumn('subdomain');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->string('subdomain');
        });
    }
}
