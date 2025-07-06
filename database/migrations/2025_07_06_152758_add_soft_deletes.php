<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletes extends Migration
{
    public function up()
    {
        Schema::table('rosters', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('roster_assignments', function (Blueprint $table) {
            $table->softDeletes();
        });
       
    }

    public function down()
    {
        Schema::table('rosters', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('roster_assignments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        
    }
}