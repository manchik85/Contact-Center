<template>

  <form action="/send_usermail" method="post" id="send_usermail" name="send_usermail" style="width: 100%">
    <div class="row">


      <div class="col-md-6">

        <div class="form-group">
          <label class="form-label" for="login">ИИН / Логин </label>
          <div v-if="errors_login !== ''" class="alert alert-danger a_c" role="alert">
            {{ errors_login }}
          </div>

          <div class="input-group">
            <input type="text" list="login_list" name="client_login" id="login" class="form-control" v-model="login"
                   @keypress="getLogin()">
            <datalist id="login_list">
              <option :value="login.client_login" v-for="login of this.logins"></option>
            </datalist>
            <div class="input-group-append">
              <button class="btn btn-secondary btn-w-m waves-effect waves-themed" type="button" @click="completeLogin()">
                выбрать
              </button>
            </div>
          </div>
        </div>

        <div class="px_1"></div>

        <div class="form-group">
          <label class="form-label" for="mail">Эл. почта Клиента <span class="red">*</span></label>
          <div v-if="errors_mail !== ''" class="alert alert-danger a_c" role="alert">
            {{ errors_mail }}
          </div>
          <input type="email" class="form-control" name="client_mail" id="mail" v-model="mail">
        </div>
        <hr>
        <div class="a_r">
          <button class="btn btn-lg btn-primary send_mail" type="button" @click="checkForm()"> Отправить</button>
        </div>

      </div>
      <div class="col-md-6">

        <div class="form-group">
          <label class="form-label" for="numb">Тема письма <span class="red">*</span></label>
          <div v-if="errors_title !== ''" class="alert alert-danger a_c" role="alert">
            {{ errors_title }}
          </div>
          <input type="text" class="form-control" name="client_phone" id="numb" v-model="title">
        </div>

        <div class="form-group">
          <label for="mail_body" class="form-label">Текст письма <span class="red">*</span></label>
          <div v-if="errors_body !== ''" class="alert alert-danger a_c" role="alert">
            {{ errors_body }}
          </div>
          <textarea id="mail_body" name="mail_body" rows="7" v-model="mail_body" class="form-control"></textarea>
        </div>


      </div>

    </div>
  </form>

</template>

<script>
  import axios from "axios";
  import ValidName from './mixins/ValidName';
  import ValidEmail from './mixins/ValidEmail';
  import ErrorsNameCSS from './mixins/ErrorsNameCSS';
  import ErrorsEmailCSS from './mixins/ErrorsEmailCSS';

  export default {

    props: [
      'data'
    ],

    mixins: [
      ValidName,
      ValidEmail,
      ErrorsEmailCSS,
      ErrorsNameCSS,
    ],

    data: function () {
      return {
        logins:       [],
        mails:        [],
        login:        '',
        title:        '',
        mail:         '',
        mail_body:    '',
        errors_title: '',
        errors_login: '',
        errors_mail:  '',
        errors_body:  '',
      }
    },

    // mounted() {
    //   this.checkForm();
    // },

    created() {
    },

    methods: {

      checkForm() {
        this.errors_title = '';
        this.errors_login = '';
        this.errors_mail  = '';
        this.errors_body  = '';

        if (!this.validEmail(this.mail)) {
          this.errors_mail = 'Укажите Электонную почту!';
          return false;
        }  else {
          this.errors_mail = "";
        }

        if (!this.validName(this.title)) {
          this.errors_title = 'Укажите Тему письма!';
          return false;
        }  else {
          this.errors_title = "";
        }

        if (!this.validName(this.mail_body)) {
          this.errors_body = 'Укажите Текст письма!';
          return false;
        }  else {
          this.errors_body = "";
        }

        axios.post('/send_usermail', {
          users_id:    this.data.users_id ,
          mail_adress: this.mail ,
          mail_title:  this.title ,
          mail_body:   this.mail_body ,
        }).then((d) => {

          this.login     = '';
          this.title     = '';
          this.mail      = '';
          this.mail_body = '';

          console.log(d);

          $('.send_mail').html('Письмо отправлено').removeClass('btn-primary').addClass('btn-success');

        }).catch((e)=>{ console.log('error'); });

      },

      getLogin() {
        axios.post('api/get_login_clients', {client_login: this.login})
          .then((d) => {
            this.logins = d.data;
          })
          .catch((e) => {
          });
      },
      getLoginMail() {
        axios.post('api/get_mail_clients', {client_mail: this.mail})
          .then((d) => {
            this.mails = d.data;
          })
          .catch((e) => {
          });
      },
      completeLogin() {
        axios.post('api/get_single_clients', {client_login: this.login})
          .then((d) => {
            this.mail = d.data[0].client_mail;

          })
          .catch((e) => {
          });
      },
      completeMail() {
        axios.post('api/get_single_mail_clients', {client_mail: this.mail})
          .then((d) => {

            this.login = d.data[0].client_login;

          })
          .catch((e) => {
            console.log(e);
          });
      },

    },


  }
</script>
