<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_balances', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->string('mem_class')->nullable();
            $table->string('pmc_id')->unique()->index(); // e.g. D7126
            $table->string('name');
            $table->string('department')->nullable();
            $table->string('member_status')->default('ACTIVE');
            $table->string('carenderia_waived')->nullable();
            $table->decimal('share_capital', 12, 2)->default(0.00);
            $table->decimal('short_term_loan', 12, 2)->default(0.00);
            $table->decimal('carenderia_bal', 12, 2)->default(0.00);
            $table->decimal('consumer_bal', 12, 2)->default(0.00);
            $table->decimal('long_term_loan', 12, 2)->default(0.00);
            $table->decimal('total_balances', 12, 2)->default(0.00);
            $table->text('consumer_remarks')->nullable();
            $table->string('exit_cancellation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_balances');
    }
};