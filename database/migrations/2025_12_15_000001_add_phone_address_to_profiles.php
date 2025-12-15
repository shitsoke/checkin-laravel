<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'phone')) {
                $table->string('phone')->nullable()->after('display_name');
            }
            if (!Schema::hasColumn('profiles', 'address')) {
                $table->string('address', 500)->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('profiles', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
};

