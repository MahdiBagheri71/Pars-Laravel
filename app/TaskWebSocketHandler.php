<?php
namespace App;

use App\Models\NotificationUser;
use App\Models\Tasks;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class TaskWebSocketHandler implements MessageComponentInterface
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
        $arr_msg = explode('_',$msg);
        if(count($arr_msg)==2 && trim($arr_msg[0]) == 'tasks'){
            $tasks = NotificationUser::where('user_id',(int)$arr_msg[1])->orderBY('id','DESC')->where('show',0)->get();
            $connection->send(json_encode($tasks));
        }elseif(count($arr_msg)==3 && trim($arr_msg[0]) == 'time'){
            $task = Tasks::where('id',(int)$arr_msg[2])->first();
            if($task){
                if(trim($arr_msg[1]) == 'start' && $task->time_tracking_start<=0){
                    Tasks::where('id',(int)$arr_msg[2])->update(['time_tracking_start'=>time()]);
                }else if(trim($arr_msg[1]) == 'stop' && $task->time_tracking_start>0){
                    Tasks::where('id',(int)$arr_msg[2])->update(['time_tracking'=>($task->time_tracking+(time()-$task->time_tracking_start)),'time_tracking_start'=>0]);
                }
            }
        }elseif(count($arr_msg)==3 && trim($arr_msg[0]) == 'notification'){
            NotificationUser::where('user_id',(int)$arr_msg[1])->where('show',0)->where('id', (int)$arr_msg[2])
                ->update(['show' => 1]);
        }
    }
}
