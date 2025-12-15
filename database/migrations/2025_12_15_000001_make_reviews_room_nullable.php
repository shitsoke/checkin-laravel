<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Drop existing foreign then make nullable and re-add with onDelete set null
            $table->dropForeign(['room_id']);
            $table->unsignedBigInteger('room_id')->nullable()->change();
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->unsignedBigInteger('room_id')->nullable(false)->change();
            $table->foreign('room_id')->references('id')->on('rooms');
        });
    }
};
