<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('room_type_id')->nullable()->after('user_id');
            $table->foreign('room_type_id')->references('id')->on('room_types')->onDelete('set null');
        });

        // Backfill room_type_id for reviews that reference a room
        DB::table('reviews')
            ->whereNotNull('room_id')
            ->join('rooms', 'reviews.room_id', '=', 'rooms.id')
            ->update(['reviews.room_type_id' => DB::raw('rooms.room_type_id')]);
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['room_type_id']);
            $table->dropColumn('room_type_id');
        });
    }
};
