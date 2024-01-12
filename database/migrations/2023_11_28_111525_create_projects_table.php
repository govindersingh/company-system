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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('project_type', ['Fixed', 'Hourly']);
            $table->json('milestones_rate')->nullable();
            $table->double('hourly_rate', 8, 2)->nullable();
            $table->double('budget', 8, 2)->nullable();
            $table->enum('status', ['Open', 'Close', 'Cancel']);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
