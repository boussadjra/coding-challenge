<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tooltip')->nullable();
            $table->string('color')->nullable();
            $table->integer('x')->nullable();
            $table->integer('y')->nullable();
            $table->integer('id_graph')->unsigned();
            $table->foreign('id_graph')->references('id')->on('graphs')->onDelete('cascade');
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
        Schema::dropIfExists('nodes');
    }
}
