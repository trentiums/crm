<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadProductServicePivotTable extends Migration
{
    public function up()
    {
        Schema::create('lead_product_service', function (Blueprint $table) {
            $table->unsignedBigInteger('lead_id');
            $table->foreign('lead_id', 'lead_id_fk_9843507')->references('id')->on('leads')->onDelete('cascade');
            $table->unsignedBigInteger('product_service_id');
            $table->foreign('product_service_id', 'product_service_id_fk_9843507')->references('id')->on('product_services')->onDelete('cascade');
        });
    }
}
