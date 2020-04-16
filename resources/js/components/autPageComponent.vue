<template>
  <div>
    <div class="page-logo m-0 w-100 align-items-center justify-content-center rounded border-bottom-left-radius-0 border-bottom-right-radius-0 px-4">
      <img src="img/logo.png" alt="КОнтакт Центр" aria-roledescription="logo">
      <span class="page-logo-text mr-1"> &nbsp; {{ data.name }}</span>
    </div>
    <div class="card p-4 border-top-left-radius-0 border-top-right-radius-0">

      <div class="form-group">
        <label class="form-label" for="password">Пароль</label>

        <input v-model="pass" @click="resetForm()" type="password" id="password" name="password" class="form-control"
               placeholder="Введите Пароль" value="" required @keyup.enter="loginUser()">
        <div v-if="errorPass" class="alert alert-danger a_c" role="alert">Введите <strong>Пароль</strong></div>


      </div>
      <button type="submit" class="btn btn-primary btn-pills waves-effect waves-themed" id="login_but"
              @click="loginUser()">Войти
      </button>
      <div v-if="accessError" class="alert alert-danger a_c" role="alert"><strong>Пользователь не найден!</strong>
        <br> Проверьте данные и повторите вход
      </div>

      <div v-if="accessStric" class="alert alert-danger a_c" role="alert"><strong>Вход заблокирован!</strong>
        <br> Превышено количество попыток входа. Проверьте данные и повторите попытку через 30 секунд.
      </div>

    </div>


  </div>
</template>


<script>

  import axios from "axios";

  import ValidEmail from './mixins/ValidEmail';
  import ValidPass  from './mixins/ValidPass';

  export default {

    props: [
      'data'
    ],

    data() {
      return {
        accessStric: false,
        accessError: false,
        errorEmail: false,
        errorPass: false,
        email: this.data.email,
        pass: '',
      }
    },

    mixins: [
      ValidEmail,
      ValidPass
    ],


    methods: {

      loginUser() {
        this.resetForm();
        if (!this.validEmail(this.email)) {
          this.errorEmail = true;
        }
        if (!this.validPass(this.pass)) {
          this.errorPass = true;
        }
        if (this.validEmail(this.email) && this.validPass( this.pass )) {
          const dataObj = {
            email: this.email,
            password: this.pass,
          };
          axios.post('api/check_user_access', dataObj).then(( response ) => {

            if (parseInt(response.data) === 1) {
              axios.post('api/operator_in', { id: this.data.id }).then(( d ) => {
                document.location.href='/';
              });
            }

            else if( parseInt(response.data) > 2 ) {
              this.accessStric = true;
              this.accessError = false;
            }

            else {
              this.accessStric = false;
              this.accessError = true;
            }
          }).catch(function (error) {
            this.accessError = true;
            console.log(error);
          });
        }
      },

      resetForm() {
        this.errorEmail = false;
        this.errorPass = false;
        this.accessError = false;
      }
    },


    created() {
      Echo.join('poll')
        .here(user => {
          console.log('socket is started!');
        })
        .listen('pollUser', (event) => {

          axios.post('api/pong_operator_out', {user_id: $('#id_comm').val(), user_time: event.message.date}).then((d) => {


            console.log(event.message.date);
            console.log('===============================');
            console.log(d.data);

          }).catch(error => {

            console.log(error);
          });
        });
    },


  }
</script>
