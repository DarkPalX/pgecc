<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS employee_search_view");
        
        DB::statement("
            CREATE VIEW employee_search_view AS
            SELECT 'carenderia' AS module, employee_id, total, date, upload_id FROM carenderia_items
            UNION ALL
            SELECT 'loan' AS module, employee_id, total, date, upload_id FROM loan_items
            UNION ALL
            SELECT 'grocery' AS module, employee_id, total, date, upload_id FROM grocery_items
            UNION ALL
            SELECT 'payment' AS module, employee_id, total, date, upload_id FROM payment_items
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS employee_search_view");
    }
};