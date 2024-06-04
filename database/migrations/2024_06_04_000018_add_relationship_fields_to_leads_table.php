<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToLeadsTable extends Migration
{
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('lead_status_id')->nullable();
            $table->foreign('lead_status_id', 'lead_status_fk_9843505')->references('id')->on('lead_statuses');
            $table->unsignedBigInteger('lead_channel_id')->nullable();
            $table->foreign('lead_channel_id', 'lead_channel_fk_9843506')->references('id')->on('lead_channels');
            $table->unsignedBigInteger('lead_conversion_id')->nullable();
            $table->foreign('lead_conversion_id', 'lead_conversion_fk_9843508')->references('id')->on('lead_conversions');
        });
    }
}
