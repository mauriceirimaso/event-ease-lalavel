<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add password if missing (placed after email)
            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password')->after('email');
            }
            
            // Add is_system if missing (placed after password)
            if (!Schema::hasColumn('users', 'is_system')) {
                $table->boolean('is_system')->default(false)->after('password');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_system');
            // Password column kept for authentication
        });
    }
};