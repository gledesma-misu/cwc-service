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
        Schema::create('ta_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('request_id');
            $table->integer('user_id');
            $table->integer('division_id');
            $table->integer('request_by');
            $table->string('request_type');
            $table->text('description');
            $table->text('request_date');
            $table->text('file_attachement')->nullable();
            $table->integer('status')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_assistances');
    }
};
