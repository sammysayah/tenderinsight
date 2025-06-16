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
        Schema::create('csmldocs', function (Blueprint $table) {
            $table->id();
            $table->string('document_name');
            $table->string('document_type');
            $table->integer('year');
            $table->date('expiry_date');
            $table->string('document_title')->nullable(); // New column for file title
            $table->string('file_path');
            $table->timestamps();

            $table->unique(['document_type', 'year'], 'unique_document_type_year'); // Prevent duplicate document type/year
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csmldocs');
    }
};
