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
        Schema::table('product_services', function (Blueprint $table) {
            $table->dropColumn('company_user_id');
            $table->unsignedBigInteger('company_id')->after('id')->nullable();
            $table->foreign('company_id', 'company_fk_9843290')->references('id')->on('companies');
            $table->unsignedBigInteger('user_id')->after('company_id')->nullable();
            $table->foreign('user_id', 'user_fk_9843291')->references('id')->on('users');
        });
    }
};
