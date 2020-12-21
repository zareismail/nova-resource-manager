<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovaResourceGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nova_resource_groups', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->text('icon')->nullable();
            $table->boolean('visible')->default(true);
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nova_resource_groups');
    }
}
