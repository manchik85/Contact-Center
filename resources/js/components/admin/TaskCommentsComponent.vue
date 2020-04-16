<template>

  <div id="panel-2" class="panel" data-panel-fullscreen="false">
    <div class="panel-hdr">
      <h2 class="history_show" style="cursor: pointer">
        История заявки (Алгоритм решения)
      </h2>
    </div>
    <div class="panel-container disp_n" id="history">
      <div class="panel-content p-0">
        <div class="d-flex flex-column">
          <div class="bg-subtlelight-fade custom-scroll">
            <div class="h-100">

              <div class="d-flex flex-row px-3 pt-3 pb-2" v-for="message of this.messages">

                <div class="ml-3">
                  <div class="d-block fw-700 text-dark pad_3">{{ message.name }} &nbsp; <span class="fw-300">({{ status[0][message.status] }})</span>
                  </div>

                  <div class="pad_3" v-html="message.task_comment"></div>
                  <div class="a_r fw-300 pad_3">{{ message.date_task }}</div>
                </div>
              </div>


              &nbsp;
            </div>
          </div>
        </div>
      </div>
      <!--<div class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 bg-faded">-->
        <!--<textarea rows="4" v-model="message"-->
                  <!--class="form-control rounded-top border-bottom-left-radius-0 border-bottom-right-radius-0 border"-->
                  <!--placeholder="То, как будет решена Задача: ..."></textarea>-->

        <!--<div class="d-flex align-items-center py-2 px-2 bg-white border border-top-0 rounded-bottom bg-primary a_r">-->


                    <!--<span class="btn btn-primary btn-sm ml-auto ml-sm-0" @click="checkForm()">-->
                        <!--Отправить-->
                    <!--</span>-->


        <!--</div>-->
      <!--</div>-->
    </div>
  </div>

</template>
<script>

  export default {

    props: [
      'data'
    ],

    data: function () {
      return {
        message: '',
        messages: [],
        status: [{
          5: 'Оператор',
          10: 'Разработчик',
          90: 'Администратор',
        }],

      }
    },

    // mounted() {
    //   this.checkForm();
    // },

    created() {
    },


    methods: {

      checkForm: function () {

        if (this.message && this.message !== '') {
          axios.post('api/add_mess', {
            task_comment: this.message,
            user_id: this.data.users_id,
            task_id: this.data.task_id
          })
            .then((d) => {

              if (d.data > 0) {

                this.messages.unshift(
                  {
                    name: this.data.username,
                    status: this.data.status,
                    date_task: this.data.date,
                    task_comment: this.message,
                  }
                );
                this.message = '';
              }

            })
            .catch((e) => {
              console.log(e);
            });
        }
      },
    },

    mounted() {

      axios.post('api/get_mess', {
        task_id: this.data.task_id
      })
        .then((d) => {

          console.log(d.data);

          if ( d.data.length > 0) {

            this.messages = d.data;
            this.message  = '';
          }

        })
        .catch((e) => {
          console.log(e);
        });

    },

  }
</script>
