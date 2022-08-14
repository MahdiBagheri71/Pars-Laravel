<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role = Role::create(['name' => 'client']);
        $role2 = Role::create(['name' => 'admin']);
        Permission::create(['name' => 'edit status tasks'])->assignRole($role)->assignRole($role2);
        Permission::create(['name' => 'add me tasks'])->assignRole($role)->assignRole($role2);
        Permission::create(['name' => 'view tasks'])->assignRole($role)->assignRole($role2);
        Permission::create(['name' => 'edit me task'])->assignRole($role)->assignRole($role2);
        Permission::create(['name' => 'view all tasks'])->assignRole($role2);
        Permission::create(['name' => 'edit all tasks'])->assignRole($role2);
        Permission::create(['name' => 'delete tasks'])->assignRole($role2);
        Permission::create(['name' => 'add tasks'])->assignRole($role2);

        //By Mahdi Insert user
        DB::table('users')->insert(
            array(
                array(
                    'id' => 1 ,
                    'name' => 'مهدی',
                    'last_name' => 'باقری ورنوسفادرانی',
                    'email' => 'MB730@rocketmail.com',
                    'username' => 'Mahdi71',
                    'password' =>  Hash::make('123456789'),//123456789
                    'api_token' => '6F8iwqdZadgEjakiCt37wBSdp',
                    'is_admin' => 1
                ),

                array(
                    'id' => 2 ,
                    'name' => 'محسن',
                    'last_name' => 'زمانی',
                    'email' => 'Mohsen@gmail.com',
                    'username' => 'Mohsen',
                    'password' => Hash::make('123456789'),//123456789
                    'api_token' => Str::random(25),
                    'is_admin' => 0
                ),


                array(
                    'id' => 3,
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


        $user = User::where('id',1)->first();
        $user->assignRole(['client', 'admin']);

        $user = User::where('id',2)->first();
        $user->assignRole(['client']);

        $user = User::where('id',3)->first();
        $user->assignRole(['client']);
    }
}
