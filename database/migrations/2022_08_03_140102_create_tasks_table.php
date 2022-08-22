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
            $table->string('status',50);// ['cancel', 'success'  , 'retarded' , 'delete', 'doing','planned']
//            $table->enum('status', ['cancel', 'success'  , 'retarded' , 'delete', 'doing','planned']);// ['cancel', 'success'  , 'retarded' , 'delete', 'doing','planned']
//            $table->enum('status', array_keys(config('enums.task_status')));// ['cancel', 'success'  , 'retarded' , 'delete', 'doing','planned']
            $table->date('date_start');
            $table->time('time_start', 0);
            $table->date('date_finish');
            $table->time('time_finish', 0);
            $table->unsignedBigInteger('time_tracking');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('create_by_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('create_by_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('tasks');
    }
}
