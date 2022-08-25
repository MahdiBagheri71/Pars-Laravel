$('#task_list_show_socket').removeClass('dropdown-menu');
$('#task_list_show_socket_a').removeClass('dropdown-toggle');

Echo.channel('Notification.'+user_id)
    .listen('NotificationEvents', (e) => {
        var data = e.notifications;
        $('#task_list_show_socket_a').removeClass('dropdown-toggle');
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
                '<a type="button"  onclick="viewNotification('+notification.id+')" class="notification-user-row dropdown-item" data-href="'+SITEURL+notification.link+'">\n' +
                notification.notification +
                '</a></li>';
        });
        $('#task_list_show_socket').html(notification_html);
    });


function viewNotification(not_id){
    websocket.send('notification_'+user_id+'_'+not_id);

    websocket.send('tasks_'+user_id);
}
