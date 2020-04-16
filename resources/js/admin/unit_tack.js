

import axios from "axios";

window.Vue = require('vue');

// import VueTheMask from 'vue-the-mask';
// Vue.use(VueTheMask);

// import Inputmask from 'inputmask';
// Vue.use(Inputmask);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('task-comments', require('../components/admin/TaskCommentsComponent.vue').default);

const app = new Vue({
  el: '#app'
});


const controls = {
  leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
  rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
};

const runDatePicker = function () {
  // range picker
  $('#task_off_form').datepicker(
    {
      todayHighlight: true,
      language: 'ru',
      format: 'dd.mm.yyyy',
      templates: controls
    });
};

const Task = {
  init: function () {

    $('#task_priority').on('change', function () {
      confirm('Подтвердите действие!');
    });

    $('#complete').on('change', function () {
        const new_complete = $('#complete').val();

        if (new_complete == 1) {
            document.getElementById('comment_div').style.display = 'none';
            document.getElementById('developer_div').style.display = 'block';
        } else {
            document.getElementById('developer_div').style.display = 'none';
            document.getElementById('comment_div').style.display = 'block';
        }

      //confirm('Подтвердите действие!');
    });

    $('.ch_complete_confirm').on('click', function () {
        const new_complete      = $('#complete').val();
        const comment_complete = $('#comment_complete').val();
        const task_id       = $('#task_id').val();
        const users_id      = $('#users_id').val();
        const users_name      = $('#users_name').val();
        const developer_id    = $('#developer').val();

        console.log("new_complete = ", new_complete);
        console.log("comment_complete = ", comment_complete);
        console.log("task_id = ", task_id);
        console.log("users_id = ", users_id);
        console.log("users_name = ", users_name);
        console.log("developer_id = ", developer_id);

        if((new_complete==1 && developer_id !=='') || (new_complete!=='' && comment_complete!=='')) {
            $('#complete_id').val(new_complete);
            if (developer_id !== '') {
                $('#developer_id').val(developer_id);
            }

            axios.post('api/ch_data_work_stage', {
                'new_complete': new_complete,
                'comment_complete': comment_complete,
                'task_id': task_id,
                'users_name': users_name,
                'users_id': users_id
            }).then((d) => {
                if( parseInt(d.data)>0){
                    $('.task-edit-but').click();
                }
                alert('Изменения успешно сохранены!');
                console.log(d.data)
            });
        } else {
            alert('Заполните обязательные поля!');
        }
    })

    $('.ch_date_confirm').on('click', function () {
      const new_date      = $('#task_off_form').val();
      const justification = $('#justification').val();
      const task_id       = $('#task_id').val();
      const users_id      = $('#users_id').val();
      const users_name      = $('#users_name').val();

      if(new_date!=='' && justification!=='') {
        $('#task_off').val(new_date);
        $('#old_date').html(new_date);

        axios.post('api/ch_data_complete', {
          'new_date': new_date,
          'justification': justification,
          'task_id': task_id,
          'users_name': users_name,
          'users_id': users_id
        }).then((d) => {
          if( parseInt(d.data)>0){
            $('.task-edit-but').click();
          }
            alert('Изменения успешно сохранены!');
            console.log(d.data)
        });
      } else {
          alert('Заполните обязательные поля!');
      }
    })

    $('.ch_response_confirm').on('click', function () {
      const comment_complete = $('#comment_response').val();
      const task_off      = $('#task_off').val();
      const task_id       = $('#task_id').val();
      const users_id      = $('#users_id').val();
      const users_name      = $('#users_name').val();

      console.log(comment_response);

      if(comment_complete!=='') {
          axios.post('api/ch_response_complete', {
              'comment_complete': comment_complete,
              'task_id': task_id,
              'users_name': users_name,
              'users_id': users_id,
              'task_off': task_off
          }).then((d) => {
              if( parseInt(d.data)>0){
                  $('.task-response-but').click();
              }
              alert('Изменения успешно сохранены!');
              console.log(d.data)
          });
      } else {
          alert('Заполните обязательные поля!');
      }
    })

    $('.history_show').on('click', function () {
        $('#history').slideToggle();
    })

    $('.task-close-but').on('click', function () {
        var result = confirm('Вы уверены что хотите закрыть заявку?');

        console.log(result);

        if (result == true) {
            $('#task_is_close').val(1);
            $('.task-complete-close-but').click();
        }
    })
  },


};

$(document).ready(function () {
  Task.init();
  runDatePicker();
});
