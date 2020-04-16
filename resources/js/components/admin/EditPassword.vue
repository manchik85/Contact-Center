<template>
  <div class="container div_c">

    <input id="userST" type="hidden" name="userST" value=0>

    <div class="div px_20 float_n clb"></div>


      <div class="row">
        <div class="col-lg-8 col-xl-6 col-md-10 panel-content">
          <div class="px_10"></div>

          <div class="input-group flex-nowrap" v-bind:class="errors_newpass_css">
            <input type="password" class="form-control" name="new-password" value="" required
                   placeholder="Имеющийся Пароль"
                   v-model.trim="oldpass"
                   aria-describedby="addon-wrapping-right">

            <div class="input-group-append">
              <span class="input-group-text"><i class="fal fa-lock fs-xl"></i></span>
            </div>

          </div>
          <div v-if="errors_oldpass !== ''" class="alert alert-danger a_c" role="alert">
            {{errors_oldpass}}
          </div>
          <div class="px_10"></div>
          <div class="px_10"></div>
          <div v-if="onldpass_button" class="btn btn-sm btn-primary pull-right" @click="checkOldPass"> &nbsp; &nbsp; Далее
            &nbsp; &nbsp; <span class="glyphicon glyphicon-chevron-right"></span>
          </div>
        </div>
      </div>

    <div v-show="form_ch_pass">
      <div class="row">
        <div class="col-lg-8 col-xl-6 col-md-10 panel-content">
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
      <div v-if="progress_bar">
        <div class="footer py-3 pad_20">
          <button class="btn btn-primary" id="password-batton" @click="confirmPassword">
            <i class="fal fa-pencil-alt"></i> &nbsp;Сохранить изменения&nbsp;
          </button>
        </div>
      </div>
    </div>
    <div class="div px_20 float_n clb"></div>
    <div class="clb"></div>
  </div>
</template>

<script>
  import  axios                from 'axios';
  import  $                    from 'jquery';

  import  ValidPass            from './mixins/ValidPass';
  import  ErrorsOldPassCSS     from './mixins/ErrorsOldPassCSS';
  import  ErrorsPassConfirmCSS from './mixins/ErrorsPassConfirmCSS';
  import  ErrorsNewPassCSS     from './mixins/ErrorsNewPassCSS';
  import  ComplexityPassword   from './mixins/ComplexityPassword';

  export default {

    data: function () {
      return {
        form_ch_pass: false,
        onldpass_button: true,
        passconfirm: "",
        errors_passconfirm: "",
      }
    },

    mixins: [
      ValidPass,
      ErrorsOldPassCSS,
      ErrorsPassConfirmCSS,
      ErrorsNewPassCSS,
      ComplexityPassword,
    ],

    methods: {

      checkOldPass: function (e) {

        this.errors_oldpass = "";

        if (!this.validPass(this.oldpass)) {
          this.errorsOldPassCSS("Введите имеющийся пароль!");
          e.preventDefault();
          return false;
        }

        else {
          axios.post('/verification_password', {pass: this.oldpass}).then((response) => {
            if (response.data === false) {
              this.errorsOldPassCSS("Вы ввели не верный пароль!");
              e.preventDefault();
              return false;
            }
            else {
              this.errors_oldpass_css['has-error']   = false;
              this.errors_oldpass_css['has-confirm'] = true;
              this.form_ch_pass                      = true;
              this.onldpass_button                   = false;
              this.errors_oldpass                    = "";
            }
          });
        }
      },

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

          axios.post('/chenge_self_passw', { oldPassword: this.oldpass, newPassword:this.newpass, passwordConfirm:this.passconfirm }).then((response) => {

            console.log(response.data.st);

            if (response.data.st === 1) {
              $('#password-batton').removeClass('btn-danger').removeClass('btn-primary').addClass('btn-success').html('<i class="fal fa-check"></i> &nbsp;Изменения сохранены');
            }
            else {
              this.errors_oldpass_css['has-error']       = true;
              this.errors_newpass_css['has-error']       = true;
              this.errors_passconfirm_css['has-error']   = true;

              this.errors_oldpass_css['has-confirm']     = false;
              this.errors_newpass_css['has-confirm']     = false;
              this.errors_passconfirm_css['has-confirm'] = false;

              $('#password-batton').removeClass('btn-primary').removeClass('btn-success').addClass('btn-danger').val('Что-то не так пошло (((');
            }
          });
        }
      },
    }
  }
</script>
