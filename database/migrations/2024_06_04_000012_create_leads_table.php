<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_user_id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_size')->nullable();
            $table->string('company_website')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('time_line')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('deal_amount', 15, 2)->nullable();
            $table->string('win_close_reason')->nullable();
            $table->date('deal_close_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
