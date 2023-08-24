<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grants', function (Blueprint $table) {
            $table->id();
            $table->integer('value')->nullable();
            $table->string('source')->nullable();
            $table->unsignedBigInteger('family_id')->nullable();
            $table->foreign('family_id')
                ->references('id')
                ->on('families')
                ->onDelete('cascade');
            $table->unsignedBigInteger('individual_id')->nullable();
            $table->foreign('individual_id')
                ->references('id')
                ->on('individuals')
                ->onDelete('cascade');
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
        Schema::dropIfExists('grants');
    }
}
