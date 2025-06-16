<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('csmlbusis', function (Blueprint $table) {
        $table->id();
        $table->string('client_name');
        $table->string('business_type');
        $table->integer('year');
        $table->string('amount')->nullable();
        $table->date('expiry_date');
        $table->enum('bid_status', ['Progress', 'won', 'lost']);
        // $table->string('file_path');
        $table->string('file_path')->nullable()->change();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('csmlbusis');
}

};
