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
            $table->string('api_token', 80)->unique()->nullable();//By mahdi api_token
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
                    'password' =>  Hash::make('123456789'),//123456789
                    'api_token' => '6F8iwqdZadgEjakiCt37wBSdp',
                    'is_admin' => 1
                ),

                array(
                    'name' => 'محسن',
                    'last_name' => 'زمانی',
                    'email' => 'Mohsen@gmail.com',
                    'username' => 'Mohsen',
                    'password' => Hash::make('123456789'),//123456789
                    'api_token' => Str::random(25),
                    'is_admin' => 0
                ),


                array(
                    'name' => 'بهروز',
                    'last_name' => 'قاسمی',
                    'email' => 'behrooz@gmail.com',
                    'username' => 'behrooz',
                    'password' => Hash::make('123456789'),//123456789
                    'api_token' => Str::random(25),
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
