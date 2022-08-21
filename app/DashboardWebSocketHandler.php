<?php

namespace App;

use App\Models\Tasks;
use App\Models\TaskStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class DashboardWebSocketHandler implements MessageComponentInterface
{

    public function onOpen(ConnectionInterface $connection)
    {
        // TODO: Implement onOpen() method.
        $socketId = sprintf('%d.%d', random_int(1, 1000000000), random_int(1, 1000000000));
        $connection->socketId = $socketId;
        $connection->app = new \stdClass();
        $connection->app->id = 'my_app';
        $connection->send("open");
    }


    public function onClose(ConnectionInterface $connection)
    {
        // TODO: Implement onClose() method.
        $connection->send("close");
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        // TODO: Implement onError() method.
        $connection->send("error");
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $msg)
    {
        // TODO: Implement onMessage() method.
        $arr_msg = explode('_', $msg);
        if (count($arr_msg) == 2 && trim($arr_msg[0]) == 'tasks') {
            $tasks = Tasks::select(DB::raw('count(id) as total, status'))->where('user_id', $arr_msg[1])->groupBy('status')->get()->keyBy('status')->all();
            $tasks_status = TaskStatus::byValue();//get status task by value
            $task_data = [];
            $total = 0;
            foreach ($tasks_status as $task_status => $task_info) {
                $count = isset($tasks[$task_status]) ? $tasks[$task_status]['total'] : 0;
                $task_data[] = [
                    'name' => __($task_info['label']),
                    'y' => $count,
                    'drilldown' => $task_status,
                    'color' => $task_info['color']
                ];
                $total += $count;
            }
            $connection->send(json_encode(['tasks' => $task_data, "total" => $total]));
        } else {
            $connection->send($msg);
        }
    }
}
