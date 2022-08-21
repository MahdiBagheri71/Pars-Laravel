var wsUri =  "ws://127.0.0.1:8081/task-websocket";
$('#task_list_show_socket').removeClass('dropdown-menu');
$('#task_list_show_socket_a').removeClass('dropdown-toggle');
websocket = new WebSocket(wsUri);
websocket.onopen = function (ev) { // connection is open
    console.log("open  --- > ")
    console.log(ev)
    websocket.onmessage = function (ev) {
        var data_json = ev.data;
        $('#task_list_show_socket_a').removeClass('dropdown-toggle');
        if(isJson(data_json)){
            var data = JSON.parse(data_json);
            $('#number_task_list').text(data.length);
            var task_html = '';
            if(data.length>0){
                $('#task_list_show_socket').addClass('dropdown-menu');
                $('#task_list_show_socket_a').addClass('dropdown-toggle');
            }else{
                $('#task_list_show_socket').removeClass('dropdown-menu');

            }
            $.each(data,function (key,task){
               task_html += '<li><a class="dropdown-item" href="'+SITEURL+'/task/'+task.id+'">\n' +
                   task.name +
                   '</a></li>';
            });
            $('#task_list_show_socket').html(task_html);
        }
    };
    setInterval(function (){
        websocket.send('tasks_'+user_id);
    },3000)
}
websocket.onclose = function (ev) {

    console.log("onclose  --- > ")
    console.log(ev)
};
function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
