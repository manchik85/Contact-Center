require('./bootstrap-soc');

import axios from "axios";






Echo.join('poll')
  .here(user => {
    console.log('socket is started!');
  })
  .listen('pollUser', (event) => {

    axios.post('api/pong_operator', {user_id: $('#id_comm').val(), user_time: event.message.date}).then((d) => {


      console.log(event.message.date);
      console.log('===============================');
      console.log(d.data);

    }).catch(error => {

      console.log(error);
    });
  });

Echo.join('task')
  .here(user => {
    console.log('task is started!');
  })
  .listen('chenTaskUser', (event) => {

    console.log(event);

    axios.post('api/task_has_operator', { id: event.message.task, users_id: event.message.host }).then((d) => {


      console.log(d);

      if(d.data > 0 ) {
        $('#badge_task').html(`<a href="#" class="header-icon"><i class="fal fa-bell swing animated"></i></a>`);

        $('#task_id').html(event.message.task);
        $('#task_name').html(event.message.title);
        $('#task_message').html(event.message.task_mess);

        $('#task_mess_bat').click();
      }
    });

  });



$(document).ready(function () {

  $('.filter_show').click( function () {
    $('#filter').slideToggle();
  });

});
