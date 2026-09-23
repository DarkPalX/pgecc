<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('permission');
            $table->timestamps();
            $table->unique(['user_id', 'permission']);
        });

        // Keep the existing default account usable after authentication is enabled.
        DB::table('users')->where('email', 'superadmin@example.com')->update(['role' => 'super_admin']);
    }

    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
