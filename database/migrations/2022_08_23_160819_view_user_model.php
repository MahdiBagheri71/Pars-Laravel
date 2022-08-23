<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ViewUserModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('view_user_model', function (Blueprint $table) {
            $table->collation = 'utf8mb4_general_ci';//BY Mahdi FOr utf8mb4_general_ci collation
            $table->id();
            $table->integer('sorting');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('column_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('column_id')->references('id')->on('columns_model')->onDelete('cascade');
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
        Schema::dropIfExists('view_user_model');
    }
}
