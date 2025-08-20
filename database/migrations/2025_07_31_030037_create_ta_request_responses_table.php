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
        Schema::create('ta_request_responses', function (Blueprint $table) {
            $table->id();
            $table->integer('ta_request_id');
            $table->text('findings');
            $table->text('actions_taken');
            $table->text('recommendations');
            $table->text('remarks');
            $table->integer('performed_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ta_request_responses');
    }
};
