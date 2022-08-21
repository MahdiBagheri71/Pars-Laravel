<?php
namespace App;

use App\Models\Tasks;
use Illuminate\Support\Facades\Auth;
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
            $tasks = Tasks::where('user_id',(int)$arr_msg[1])->where('updated_at','>',date('Y-m-d H:i:s',strtotime('-5 minutes')))->get();
            $connection->send(json_encode($tasks));
        }else{
            $connection->send($msg);
        }
    }
}
