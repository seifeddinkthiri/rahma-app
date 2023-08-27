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
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('cin')->nullable();
            $table->string('phone')->nullable();
            $table->string('birth_city')->nullable();
            $table->string('social_status')->nullable();
            $table->string('kinship')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('education_level')->nullable();
            $table->string('education_place')->nullable();
            $table->string('job')->nullable();
            $table->string('job_place')->nullable();
            $table->string('photo', 100)->nullable();

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
