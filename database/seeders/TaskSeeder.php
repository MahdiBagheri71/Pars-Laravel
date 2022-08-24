<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $status_task = array(
            array(
                'value' => 'planned',
                'label' => 'Planned',
                'color' => '#6707f6'
            ),
            array(
                'value' => 'doing',
                'label' => 'Doing',
                'color' => '#2094fb'
            ),
            array(
                'value' => 'success',
                'label' => 'Success',
                'color' => '#198754'
            ),
            array(
                'value' => 'retarded',
                'label' => 'Retarded',
                'color' => '#ee9018'
            ),
            array(
                'value' => 'cancel',
                'label' => 'Cancel',
                'color' => '#f0077f'
            ),
        );

        DB::table('tasks_status')->insert($status_task);

        $tasks = array();

        $comments = array();

        $status = array(
            'planned',
            'doing',
            'success',
            'retarded',
            'cancel'
        );

        for($i=1;$i<178;$i++){

            $date = $this->randomDate();

            $user_id = rand(1,3);
            $create_by_id = rand(0,1)?$user_id:1;

            $tasks[]=array(
                'id' => $i,
                'name' => 'Task Number '.$i,
                'note' => $this->lorem(rand(1,5)),
                'status' => $status[rand(0,4)],
                'date_start' => $date['date_start'],
                'time_start' => $date['time_start'],
                'date_finish' => $date['date_finish'],
                'time_finish' => $date['time_finish'],
                'time_tracking' => rand(0,30),
                'user_id' => $user_id,
                'create_by_id' => $create_by_id,
                'sorting' => $i
            );

            for($j=1;$j<rand(2,10);$j++){

                $comments[]=array(
                    'note' => $this->lorem(rand(1,5)),
                    'task_id' => $i,
                    'create_by_id' => rand(0,1)?$user_id:$create_by_id,
                    'created_at' => date('Y-m-d H:i:s',rand(strtotime('-15 days'),time()))
                );
            }

        }

        DB::table('tasks')->insert($tasks);
        DB::table('comments_task')->insert($comments);

    }

    public function randomDate()
    {
        // Convert to timetamps
        $min = strtotime('-20 days');
        $max = strtotime('+20 days');

        // Generate random number using above bounds
        $val = rand($min, $max);

        $val_end =  $val+rand(60,9000);

        $date = array(
            'date_start' => date('Y-m-d', $val),
            'date_finish' => date('Y-m-d',$val_end),
            'time_start' => date('H:i:s', $val),
            'time_finish' => date('H:i:s',$val_end)
        );
        // Convert back to desired date format
        return $date;
    }

    public function lorem($count = 1, $max = 20, $standard = true) {
        $output = '';

        if ($standard) {
            $output = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
                'sed do eiusmod tempor incididunt ut labore et dolore magna ' .
                'aliqua.';
        }

        $pool = explode(
            ' ',
            'a ab ad accusamus adipisci alias aliquam amet animi aperiam ' .
            'architecto asperiores aspernatur assumenda at atque aut beatae ' .
            'blanditiis cillum commodi consequatur corporis corrupti culpa ' .
            'cum cupiditate debitis delectus deleniti deserunt dicta ' .
            'dignissimos distinctio dolor ducimus duis ea eaque earum eius ' .
            'eligendi enim eos error esse est eum eveniet ex excepteur ' .
            'exercitationem expedita explicabo facere facilis fugiat harum ' .
            'hic id illum impedit in incidunt ipsa iste itaque iure iusto ' .
            'laborum laudantium libero magnam maiores maxime minim minus ' .
            'modi molestiae mollitia nam natus necessitatibus nemo neque ' .
            'nesciunt nihil nisi nobis non nostrum nulla numquam occaecati ' .
            'odio officia omnis optio pariatur perferendis perspiciatis ' .
            'placeat porro possimus praesentium proident quae quia quibus ' .
            'quo ratione recusandae reiciendis rem repellat reprehenderit ' .
            'repudiandae rerum saepe sapiente sequi similique sint soluta ' .
            'suscipit tempora tenetur totam ut ullam unde vel veniam vero ' .
            'vitae voluptas'
        );

        $max = ($max <= 3) ? 4 : $max;
        $count = ($count < 1) ? 1 : (($count > 2147483646) ? 2147483646 : $count);

        for ($i = 0, $add = ($count - (int) $standard); $i < $add; $i++) {
            shuffle($pool);
            $words = array_slice($pool, 0, mt_rand(3, $max));
            $output .= ((! $standard && $i === 0) ? '' : ' ') . ucfirst(implode(' ', $words)) . '.';
        }

        return $output;
    }
}
