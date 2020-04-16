<template>
  <div>
    <div
        class="page-logo m-0 w-100 align-items-center justify-content-center rounded border-bottom-left-radius-0 border-bottom-right-radius-0 px-4">
      <img src="img/logo.png" alt="Контакт центр" aria-roledescription="logo">
      <span class="page-logo-text mr-1"> &nbsp; {{ data.name }}</span>
    </div>
    <div class="card p-4 border-top-left-radius-0 border-top-right-radius-0">

      <div class="form-group">
        <label class="form-label" for="email">E-mail</label>
        <input v-model="email" @click="resetForm()" type="email" id="email" name="email" class="form-control"
               placeholder="Введите e-mail" value="" required autofocus @keyup.enter="loginUser()">
        <div v-if="errorEmail" class="alert alert-danger a_c" role="alert">Укажите правильный <strong>e-mail</strong>
        </div>
      </div>
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

      <form method="POST" action="/login" id="login">
        <input type="hidden" name="email" v-model="email">
        <input type="hidden" name="password" v-model="pass">
      </form>

    </div>


  </div>
</template>


<script>

  import axios from "axios";

  import ValidEmail from './mixins/ValidEmail';
  import ValidPass from './mixins/ValidPass';

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
        email: '',
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
        if (this.validEmail(this.email) && this.validPass(this.pass)) {
          const dataObj = {
            email: this.email,
            password: this.pass,
          };
          axios.post('api/check_user_access', dataObj).then((response) => {
            if (parseInt(response.data) === 1) {
              $('#login').submit();
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


    mounted() {
    },


  }
</script>
