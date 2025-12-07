<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the new columns
            $table->string('first_name')->nullable()->after('id'); // Added nullable() for safety
            $table->string('middle_name')->nullable()->after('first_name'); // Add middle_name
            $table->string('last_name')->nullable()->after('middle_name');
            $table->string('role')->default('user')->after('last_name'); // Add role
            
            // CRITICAL FIX: UNCOMMENT THIS LINE to drop the legacy 'name' column
            $table->dropColumn('name'); 
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        // Drop 'first_name' only if it exists
            if (Schema::hasColumn('users', 'first_name')) {
                $table->dropColumn('first_name');
            }

        // Drop 'last_name' only if it exists
            if (Schema::hasColumn('users', 'last_name')) {
                $table->dropColumn('last_name');
            }
        
      
        });
    }
};
