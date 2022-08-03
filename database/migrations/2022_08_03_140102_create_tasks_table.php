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
            $table->set('state', ['cancel', 'success'  , 'retarded' , 'delete']);
            $table->date('date');
            $table->time('time', 0);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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

        });
    }
}
