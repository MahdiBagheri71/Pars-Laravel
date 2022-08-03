<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->collation = 'utf8mb4_general_ci';//BY Mahdi FOr utf8mb4_general_ci collation
            $table->id();
            $table->string('name');
            $table->string('last_name');//BY Mahdi add last name
            $table->string('email')->unique();
            $table->string('username')->unique();//BY Mahdi add user name
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin');//By mahdi for check admin User
            $table->rememberToken();
            $table->timestamps();
        });

        //By Mahdi Insert user
        DB::table('users')->insert(
            array(
                array(
                    'name' => 'مهدی',
                    'last_name' => 'باقری ورنوسفادرانی',
                    'email' => 'MB730@rocketmail.com',
                    'username' => 'Mahdi71',
                    'password' => '$2y$10$FeRF1yiwt8IqhcKArFiiru63o5k8VGkGbykVxfzH0PrHKdH4yfZAa',//123456789
                    'is_admin' => 1
                ),

                array(
                    'name' => 'محسن',
                    'last_name' => 'زمانی',
                    'email' => 'Mohsen@gmail.com',
                    'username' => 'Mohsen',
                    'password' => '$2y$10$FeRF1yiwt8IqhcKArFiiru63o5k8VGkGbykVxfzH0PrHKdH4yfZAa',//123456789
                    'is_admin' => 0
                ),


                array(
                    'name' => 'بهروز',
                    'last_name' => 'قاسمی',
                    'email' => 'behrooz@gmail.com',
                    'username' => 'behrooz',
                    'password' => '$2y$10$FeRF1yiwt8IqhcKArFiiru63o5k8VGkGbykVxfzH0PrHKdH4yfZAa',//123456789
                    'is_admin' => 0
                )
            )
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
