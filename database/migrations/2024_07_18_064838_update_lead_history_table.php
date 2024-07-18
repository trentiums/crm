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
        //
        Schema::table('lead_history', function (Blueprint $table) {
            $table->dropColumn('company_user_id');
            $table->unsignedBigInteger('company_id')->after('id')->nullable();
            $table->foreign('company_id', 'company_fk_9843288')->references('id')->on('companies');
            $table->unsignedBigInteger('user_id')->after('company_id')->nullable();
            $table->foreign('user_id', 'user_id_fk_7843255')->references('id')->on('users');
            $table->longText('old_lead')->after('company_user_id')->nullable();
            $table->longText('new_lead')->after('old_lead')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('lead_history', function (Blueprint $table) {
            $table->unsignedBigInteger('company_user_id');
            $table->foreign('company_user_id', 'company_user_id_fk_7843245')->references('id')->on('company_users')->onDelete('cascade');
            $table->dropColumn('company_id');
            $table->dropColumn('user_id');
            $table->dropColumn('old_lead');
            $table->dropColumn('new_lead');
        });
    }
};
