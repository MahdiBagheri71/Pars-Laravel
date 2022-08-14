<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// هر کار دارای این فیلدها است: نام، یادداشت، وضعیت, زمان (روز فعلی به عنوان تاریخ درج شده است).
class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->collation = 'utf8mb4_general_ci';//BY Mahdi FOr utf8mb4_general_ci collation
            $table->id();
            $table->string('name', 255);
            $table->text('note');
//            $table->string('status',50);// ['cancel', 'success'  , 'retarded' , 'delete', 'doing','planned']
            $table->enum('status', ['cancel', 'success'  , 'retarded' , 'delete', 'doing','planned']);// ['cancel', 'success'  , 'retarded' , 'delete', 'doing','planned']
            $table->date('date');
            $table->time('time', 0);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('create_by_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('create_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletesTz($column = 'deleted_at', $precision = 0);
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
        Schema::dropIfExists('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_user_id_foreign');
            $table->dropForeign('tasks_create_by_id_foreign');

        });
    }
}
