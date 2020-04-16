<template>
  <div class="container div_c">
    <div class="row">
      <div class="col-lg-8 col-xl-6 col-md-10 panel-content">
        <div class="px_10"></div>
        <div class="input-group flex-nowrap" v-bind:class="errors_newpass_css">
          <input id="new-password" type="password" class="form-control" name="new-password" placeholder="Пароль" value="" required
                 @keyup="complexityPassword"
                 v-model.trim="newpass"
                 aria-describedby="addon-wrapping-right">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fal fa-lock fs-xl"></i></span>
          </div>
        </div>
        <div class="progress" v-if="progress_bar">
          <div class="progress-bar progress-bar-striped bg-warning" role="progressbar"
               style="width: 20%"
               id="progress-bar"
               aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div v-if="errors_newpass !== ''" class="alert alert-danger a_c" role="alert">
          {{errors_newpass}}
        </div>
          &nbsp;
        <div v-show="progress_bar" class="input-group flex-nowrap" v-bind:class="errors_passconfirm_css">
          <input class="form-control" id="password-confirm" type="password" name="password-confirm" placeholder="Подтверждение"
                 value="" required
                 v-model.trim="passconfirm"
                 aria-describedby="addon-wrapping-right">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fal fa-lock fs-xl"></i></span>
          </div>
        </div>
        <div v-if="errors_passconfirm !== '' && progress_bar" class="alert alert-danger a_c" role="alert">
          {{errors_passconfirm}}
        </div>
      </div>
    </div>
    <div class="footer py-3 pad_20">
      <button class="btn btn-primary" id="password-batton" @click="confirmPassword">
        <i class="fal fa-pencil-alt"></i> &nbsp;Сохранить изменения&nbsp;
      </button>
    </div>

  </div>
</template>

<script>
  import axios from 'axios';
  import $ from 'jquery';
  import  ErrorsPassConfirmCSS from './mixins/ErrorsPassConfirmCSS';
  import  ErrorsNewPassCSS     from './mixins/ErrorsNewPassCSS';
  import  ComplexityPassword   from './mixins/ComplexityPassword';

  export default {
    props: [
      'data'
    ],

    data: function () {
      return {
        passconfirm: "",
      }
    },

    mixins: [
      ErrorsPassConfirmCSS,
      ErrorsNewPassCSS,
      ComplexityPassword,
    ],

    methods: {

      confirmPassword: function (e) {

        this.errors_passconfirm = "";

        if(this.newpass !== this.passconfirm) {

          this.errors_passconfirm_css['has-error']   = true;
          this.errors_passconfirm_css['has-confirm'] = false;

          this.errorsPassConfirmCSS("Пароль и подтверждение должны совпадать!");
          e.preventDefault();
          return false;
        }
        else {
          this.errors_passconfirm_css['has-error']   = false;
          this.errors_passconfirm_css['has-confirm'] = true;
          this.errors_newpass_css['has-error']       = false;
          this.errors_newpass_css['has-confirm']     = true;
          this.errors_passconfirm                    = "";

          axios.post('/chenge_users_passw', {user_id: this.data.id, newPassword:this.newpass, passwordConfirm:this.passconfirm }).then((response) => {

            if (response.data.st === 1) {
              $('#password-batton').removeClass('btn-danger').removeClass('btn-primary').addClass('btn-success').html('<i class="fal fa-check"></i> &nbsp;Изменения сохранены');
            }
            else {
              this.errors_newpass_css['has-error']       = true;
              this.errors_passconfirm_css['has-error']   = true;

              this.errors_newpass_css['has-confirm']     = false;
              this.errors_passconfirm_css['has-confirm'] = false;

              $('#password-batton').removeClass('btn-primary').removeClass('btn-success').addClass('btn-danger').val('Что-то не так пошло (((');

              console.log(response.data.st);
              console.log(this.data.id);
            }
          });
        }
      },
    }


  }
</script>
