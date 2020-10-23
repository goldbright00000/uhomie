<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSasapplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sasapplicants', function (Blueprint $table) {
            $table->increments('id');
            //$table->string('token')->unique();
            $table->string('tokenSas')->nullable()->unique();
            $table->string('applicant_id')->nullable()->unique();
            $table->string('inspection_id')->nullable();
            $table->string('correlation_id')->nullable();
            $table->string('external_user_id')->nullable();
            $table->string('type')->nullable();
            $table->string('review_answer')->nullable();
            $table->string('review_reject_type')->nullable();
            $table->text('review_moderation_comment')->nullable();
            $table->text('review_client_comment')->nullable();
            $table->string('review_status')->nullable();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('sasapplicants');
    }
}
