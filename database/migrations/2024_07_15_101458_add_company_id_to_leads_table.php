<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('company_user_id')->nullable();
            $table->foreign('company_id', 'company_fk_9843286')->references('id')->on('companies');
        });
    }
};
