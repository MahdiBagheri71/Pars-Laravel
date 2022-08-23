<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ColumnsModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('columns_model', function (Blueprint $table) {
            $table->collation = 'utf8mb4_general_ci';//BY Mahdi FOr utf8mb4_general_ci collation
            $table->id();
            $table->string('field');
            $table->string('table');
            $table->string('model');
            $table->string('label');
            $table->string('type');
            $table->string('related_table')->default('');
            $table->string('related_field')->default('');
            $table->string('related_show')->default('');
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
        Schema::dropIfExists('columns_model');
    }
}
