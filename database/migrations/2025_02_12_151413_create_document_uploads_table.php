<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentUploadsTable extends Migration
{
    public function up()
    {
        Schema::create('document_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('csmlbusi_id'); // Foreign key to csmlbusi table
            $table->string('document_title')->nullable(); // File name/title
            $table->string('file_path'); // File path for storage
            $table->timestamps();

            // Define the foreign key relationship
            $table->foreign('csmlbusi_id')
                  ->references('id')
                  ->on('csmlbusis')
                  ->onDelete('cascade'); // Cascade deletes if the parent record is deleted
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_uploads');
    }
}
