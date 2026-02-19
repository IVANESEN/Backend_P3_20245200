<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Illuminate\Database\Schema\Blueprint $table) {
        $table->id();
        $table->string('applicant_name');
        $table->timestamp('loan_date');
        $table->timestamp('return_date')->nullable(); // Para el flujo de devolución [cite: 58]
        $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
