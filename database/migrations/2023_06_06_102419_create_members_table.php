<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id')->index();
            $table->unsignedBigInteger('family_id');
            $table->foreign('family_id')
                ->references('id')
                ->on('families')
                ->onDelete('cascade');
            $table->boolean('caregiver')->default(false);
            $table->string('health_insurance');
            $table->string('name');
            $table->string('address');
            $table->string('cin');
            $table->string('phone');
            $table->string('birth_city');
            $table->string('social_status');
            $table->string('kinship');
            $table->date('birth_date');
            $table->integer('monthly_income');
            $table->string('education_level');
            $table->string('job');
            $table->string('job_place');
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
        Schema::dropIfExists('members');
    }
}
