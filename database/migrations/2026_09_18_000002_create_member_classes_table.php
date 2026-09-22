<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('member_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('carenderia_limit', 12, 2)->default(0);
            $table->decimal('consumer_limit', 12, 2)->default(0);
            $table->decimal('maximum_loan', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_classes');
    }
};
