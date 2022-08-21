var wsUri =  "ws://127.0.0.1:8081/task-websocket";
$('#task_list_show_socket').removeClass('dropdown-menu');
$('#task_list_show_socket_a').removeClass('dropdown-toggle');
websocket = new WebSocket(wsUri);

function viewNotification(not_id){
    websocket.send('notification_'+user_id+'_'+not_id);

    websocket.send('tasks_'+user_id);
}
websocket.onopen = function (ev) { // connection is open
    websocket.onmessage = function (ev) {
        var data_json = ev.data;
        $('#task_list_show_socket_a').removeClass('dropdown-toggle');
        if(isJson(data_json)){
            var data = JSON.parse(data_json);
            $('#number_task_list').text(data.length);
            var notification_html = '';
            if(data.length>0){
                $('#task_list_show_socket').addClass('dropdown-menu');
                $('#task_list_show_socket_a').addClass('dropdown-toggle');
            }else{
                $('#task_list_show_socket').removeClass('dropdown-menu');

            }
            $.each(data,function (key,notification){
                notification_html += '<li class="notification-user-row">' +
                   // '<a type="button" class="btn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">\n' +
                   // '  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>\n' +
                   // '  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>\n' +
                   // '</svg></a>'+
                   '<a type="button"  onclick="viewNotification('+notification.id+')" class="notification-user-row dropdown-item" data-href="'+SITEURL+notification.link+'">\n' +
                   notification.notification +
                   '</a></li>';
            });
            $('#task_list_show_socket').html(notification_html);
        }
    };
    websocket.send('tasks_'+user_id);
    setInterval(function (){
        websocket.send('tasks_'+user_id);
    },3000);
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
