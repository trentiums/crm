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
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('company_user_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by', 'created_by_fk_9843287')->references('id')->on('users');
            $table->unsignedBigInteger('assign_from_user_id')->nullable();
            $table->foreign('assign_from_user_id', 'assign_from_fk_9843288')->references('id')->on('users');
            $table->unsignedBigInteger('assign_to_user_id')->nullable();
            $table->foreign('assign_to_user_id', 'assign_to_fk_9843289')->references('id')->on('users');
        });
    }

};
