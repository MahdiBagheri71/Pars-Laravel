<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ViewUserModel extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //By Mahdi Insert columns model
        DB::table('columns_model')->insert(
            array(
                array(
                    'id' => 1,
                    'field' => 'id',
                    'table' => 'tasks',
                    'model' => 'Task',
                    'label' => 'ID',
                    'type' => 'number',
                    'related_table' => '',
                    'related_field' => '',
                    'related_show' => ''
                ),
                array(
                    'id' => 2,
                    'field' => 'name',
                    'table' => 'tasks',
                    'model' => 'Task',
                    'label' => 'Name',
                    'type' => 'string',
                    'related_table' => '',
                    'related_field' => '',
                    'related_show' => ''
                ),
                array(
                    'id' => 3,
                    'field' => 'note',
                    'table' => 'tasks',
                    'model' => 'Task',
                    'label' => 'Note',
                    'type' => 'text',
                    'related_table' => '',
                    'related_field' => '',
                    'related_show' => ''
                ),
                array(
                    'id' => 4,
                    'field' => 'status',
                    'table' => 'tasks',
                    'model' => 'Task',
                    'label' => 'Status',
                    'type' => 'select',
                    'related_table' => 'tasks_status',
                    'related_field' => '',
                    'related_show' => ''
                ),
                array(
                    'id' => 5,
                    'field' => 'date_start',
                    'table' => 'tasks',
                    'model' => 'Task',
                    'label' => 'Date Start',
                    'type' => 'date',
                    'related_table' => '',
                    'related_field' => '',
                    'related_show' => ''
                ),
                array(
                    'id' => 6,
                    'field' => 'time_start',
                    'table' => 'tasks',
                    'model' => 'Task',
                    'label' => 'Time Start',
                    'type' => 'time',
                    'related_table' => '',
                    'related_field' => '',
                    'related_show' => ''
                ),
                array(
                    'id' => 7,
                    'field' => 'date_finish',
                    'table' => 'tasks',
                    'model' => 'Task',
                    'label' => 'Date Finish',
                    'type' => 'date',
                    'related_table' => '',
                    'related_field' => '',
                    'related_show' => ''
                ),
                array(
                    'id' => 8,
                    'field' => 'time_finish',
                    'table' => 'tasks',
                    'model' => 'Task',
                    'label' => 'Time Finish',
                    'type' => 'time',
                    'related_table' => '',
                    'related_field' => '',
                    'related_show' => ''
                ),
                array(
                    'id' => 9,
                    'field' => 'time_tracking',
                    'table' => 'tasks',
                    'model' => 'Task',
                    'label' => 'Time Tracking',
                    'type' => 'time_tracking',
                    'related_table' => '',
                    'related_field' => '',
                    'related_show' => ''
                ),
                array(
                    'id' => 10,
                    'field' => 'user_id',
                    'table' => 'tasks',
                    'model' => 'Task',
                    'label' => 'User',
                    'type' => 'related',
                    'related_table' => 'users',
                    'related_field' => 'id,name,last_name',
                    'related_show' => 'name,last_name'
                ),
                array(
                    'id' => 11,
                    'field' => 'create_by_id',
                    'table' => 'tasks',
                    'model' => 'Task',
                    'label' => 'Create By',
                    'type' => 'related',
                    'related_table' => 'users',
                    'related_field' => 'id,name,last_name',
                    'related_show' => 'name,last_name'
                ),
            )
        );

        //By Mahdi Insert user
        DB::table('view_user_model')->insert(
            array(
                array(
                    'sorting' => 1,
                    'column_id' => 1,
                    'user_id' => 1,
                ),
                array(
                    'sorting' => 1,
                    'column_id' => 1,
                    'user_id' => 2,
                ),
                array(
                    'sorting' => 1,
                    'column_id' => 1,
                    'user_id' => 3,
                ),
                array(
                    'sorting' => 2,
                    'column_id' => 2,
                    'user_id' => 1
                ),
                array(
                    'sorting' => 2,
                    'column_id' => 2,
                    'user_id' => 2
                ),
                array(
                    'sorting' => 2,
                    'column_id' => 2,
                    'user_id' => 3
                ),
            )
        );
    }
}
