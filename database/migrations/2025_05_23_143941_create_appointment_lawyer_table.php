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
        Schema::create('appointment_lawyer', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('lawyer_id');
            $table->primary(['appointment_id', 'lawyer_id']);
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->foreign('lawyer_id')->references('id')->on('lawyers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_lawyer');
    }
};
