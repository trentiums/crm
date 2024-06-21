<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        CREATE VIEW lead_conversion_count_view AS
        SELECT
            lead_conversion_id,
            company_user_id,
            COUNT(*) AS conversion_count
        FROM
            leads
        GROUP BY
            lead_conversion_id,
            company_user_id
    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_conversion_count_view');
    }
};
