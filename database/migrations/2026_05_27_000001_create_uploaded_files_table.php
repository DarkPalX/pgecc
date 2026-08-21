<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('uploaded_files', function (Blueprint $table) {
            $table->id();
            $table->string('original_filename'); // E.g., "grocery_batch_may2026.xlsx"
            $table->string('storage_path');      // E.g., "uploads/grocery/abc123xyz.xlsx"
            $table->string('module_type');       // 'carenderia', 'loan', 'grocery', 'payment'
            $table->string('status')->default('completed'); // 'processing', 'completed', 'failed' (Great for queues)
            $table->integer('row_count')->default(0);       // Quick metric: how many rows were in the excel
            $table->foreignId('uploaded_by')->constrained('users'); // Admin user who uploaded it
            $table->timestamps();

            // Indexing for rapid filtering and searching in your file lists
            $table->index('module_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uploaded_files');
        Schema::dropIfExists('uploads');
    }
};