<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeNeighborsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_neighbors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_node')->unsigned();
            $table->foreign('id_node')->references('id')->on('nodes')->onDelete('cascade');
            $table->integer('id_node_neighbor')->unsigned();
            $table->foreign('id_node_neighbor')->references('id')->on('nodes')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('node_neighbors');
    }
}
