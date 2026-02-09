<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('dui', 10)->nullable();
            $table->string('phone_number')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('hiring_date')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dui', 'phone_number', 'birth_date', 'hiring_date']);
            $table->dropSoftDeletes();
        });
    }
};
