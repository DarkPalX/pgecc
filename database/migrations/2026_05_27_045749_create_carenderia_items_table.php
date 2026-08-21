<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carenderia_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_id')->constrained('uploaded_files')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('total', 10, 2);
            $table->date('date');
            $table->timestamps();

            // SQL Indexes optimized for the Dashboard View and filters
            $table->index(['employee_id', 'date']); 
            $table->index('date'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carenderia_items');
    }
};