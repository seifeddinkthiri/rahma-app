<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id')->index();
            $table->string('name')->nullable();
            $table->string('status')->nullable()->default('active');
            $table->string('address')->nullable();
            $table->string('caregiver_phone')->nullable();
            $table->string('photo', 100)->nullable();
            $table->boolean('wife')->nullable();
            $table->boolean('husband')->nullable();
            $table->integer('elderlies_number')->nullable();
            $table->integer('childrens_number')->nullable();
            $table->integer('other_members_number')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('families');
    }
}
