<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovaResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nova_resources', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('resource');
            $table->string('name');
            $table->text('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('visible')->default(true);
            $table->foreignId('group_id')->constrained('nova_resource_groups');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nova_resources');
    }
}
